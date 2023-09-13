<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="neosys.usuarios")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(name="usuario", type="string", length=20)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="usuario_mail", type="string", length=100)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=50)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="apellido", type="string", length=50)
     */
    private $lastName;

    /**
     * @ORM\ManyToMany(targetEntity="Role", inversedBy="users")
     * @ORM\JoinTable(name="neosys.g_usuarios_roles",
     *      joinColumns={@ORM\JoinColumn(name="username_id", referencedColumnName="usuario")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="role")}
     * )
     *
     */
    private $roles;

    /**
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setSastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function getFullName()
    {
        return $this->lastName . ', ' . $this->name;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    public function getRoles()
    {
        return $this->roles->toArray();
    }


    public function addRole(Role $role)
    {
        if ($this->roles->contains($role)) {
            return;
        }
        $this->roles->add($role);

        return $this;
    }


    public function removeRole(Role $role)
    {
        if (!$this->roles->contains($role)) {
            return;
        }
        $this->roles->removeElement($role);
    }

    /**
     * This method can be removed in Symfony 6.0 - is not needed for apps that do not check user passwords.
     *
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return null;
    }

    /**
     * This method can be removed in Symfony 6.0 - is not needed for apps that do not check user passwords.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
