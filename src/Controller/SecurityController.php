<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\DatabaseConnection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class SecurityController extends AbstractController
{

    private $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function loginGet(Security $security): Response
    {
        if ($security->isGranted("IS_AUTHENTICATED_FULLY")) {
            return $this->redirectToRoute("app_home");
        }
        return $this->render('auth/login.twig');
    }

    /**
     * @Route("/login_post", name="login_post")
     */
    public function login(Request $request, SessionInterface $session, ManagerRegistry $entityManager, AuthenticationManagerInterface $authenticationManager)
    {
        $dbConnection = "neosys";
        $dbService = DatabaseConnection::getInstance($_POST['username'], $_POST['password'], $dbConnection, $entityManager);
        $logged = $this->loginWithDatabaseService($session, $_POST['username'], $_POST['password'], $dbService);

        if ($logged == 0) {
            $this->connectToFirewallSymfony($session, $entityManager, $authenticationManager, $request);
        }

        $response = $logged != 0
            ? ["status" => "error", "code" => 400, "message" => "Credenciales inválidas"]
            : ["status" => "success", "code" => 200, "message" => "Logueado correctamente"];

        return new JsonResponse($response);
    }

    private function loginWithDatabaseService($session, $username, $password, $dbService)
    {
        $userOracle = $dbService->getUserOracle($username);

        if (empty($userOracle)) {
            return 2;
        }

        $session->set('file', $userOracle[0]['LEGAJO']);
        $session->set('lastname', $userOracle[0]['APELLIDO']);
        $session->set('name', $userOracle[0]['NOMBRE']);
        $session->set('user', $username);
        $session->set('password', $password);
        $session->set('dbConnection', $dbService);

        return 0;
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(Security $security): Response
    {
        $security->setToken(null);

        // Redirigir a la página deseada (por ejemplo, la página de inicio de sesión)
        return $this->redirectToRoute('app_login');
    }

    public function connectToFirewallSymfony($session, $entityManager, $authenticationManager, $request)
    {
        $username = $session->get('user');
        $password = $session->get('password');

        $user = $entityManager->getRepository(User::class)->findOneBy(['username' => $username]);

        if (!$user instanceof UserInterface) {
            // El usuario no fue encontrado, maneja el error apropiadamente
            return $this->redirectToRoute('pagina_de_error');
        }
        $roles = array_map(function ($role) {
            return $role->getRole();
        }, $user->getRoles());

        $roles = array_map('strval', $roles);

        // Crea un token de autenticación
        $token = new UsernamePasswordToken($user, $password, 'main', $roles);

        // Autentica al usuario
        $authenticatedToken = $authenticationManager->authenticate($token);

        // Almacena el token autenticado en el contexto de seguridad
        $this->get('security.token_storage')->setToken($authenticatedToken);

        // Dispara un evento de inicio de sesión (opcional)
        $this->eventDispatcher->dispatch(new InteractiveLoginEvent($request, $authenticatedToken));
    }
}
