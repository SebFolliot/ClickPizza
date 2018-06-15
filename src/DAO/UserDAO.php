<?php

namespace ClickPizza\DAO;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use ClickPizza\Entity\User;

class UserDAO extends DAO implements UserProviderInterface
{
        /**
         * @return \ClickPizza\Entity\User or an exception if no user found
         */
        public function userList($id) {
        $sql = "select * from t_user where user_id=?";
        $row = $this->getDb()->fetchAssoc($sql, array($id));

        if ($row)
            return $this->buildEntityObject($row);
        else
            throw new \Exception("Aucun identifiant correspondant à l'utilisateur " . $id);
    }
    
    
    /**
     * {@inheritDoc}
     */
    public function loadUserByUsername($username)
    {
        $sql = "select * from t_user where user_login=?";
        $row = $this->getDb()->fetchAssoc($sql, array($username));

        if ($row)
            return $this->buildEntityObject($row);
        else
            throw new UsernameNotFoundException(sprintf('Utilisateur "%s" non trouvé.', $username));
    }
    
    /**
     * {@inheritDoc}
     */
    public function refreshUser(UserInterface $user)
    {
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(sprintf('Les instances de "%s" ne sont pas supportées.', $class));
        }
        return $this->loadUserByUsername($user->getUsername());
    }
    
    /**
     * {@inheritDoc}
     */
    public function supportsClass($class)
    {
        return 'ClickPizza\Entity\User' === $class;
    }
    
    /**
     * Creates a User object.
     *
     * @return \ClickPizza\Entity\User
     */
    protected function buildEntityObject(array $row) {
        $user = new User();
        $user->setId($row['user_id']);
        $user->setUsername($row['user_login']);
        $user->setCivility($row['user_civility']);
        $user->setName($row['user_name']);
        $user->setFirstName($row['user_first_name']);
        $user->setEmail($row['user_email']);
        $user->setPassword($row['user_pwd']);
        $user->setSalt($row['user_salt']);
        $user->setPhoneNumber($row['user_phone_number']);
        $user->setOrderNumber($row['user_order_number']);
        $user->setRole($row['user_role']);
        return $user;
    }
    
    /**
     * Add a user in the database
     */
    
    public function add(User $user) {
        $userData = array(
        'user_login' => $user->getUsername(),
        'user_civility' => $user->getCivility(),
        'user_name' => $user->getName(),
        'user_first_name' => $user->getFirstName(),
        'user_email' => $user->getEmail(),
        'user_pwd' => $user->getPassword(),
        'user_salt' => $user->getSalt(),
        'user_phone_number' => $user->getPhoneNumber(),
        'user_order_number' => $user->getOrderNumber(),
        'user_role' => $user->getRole()
        );
        
        // Création d'un user
        $this->getDb()->insert('t_user', $userData);
        $id = $this->getDb()->lastInsertId();
        $user->setId($id);
         
    }
    
    public function delete($id) {
        // Suppression d'un utilisateur
        $this->getDb()->delete('t_user', array('user_id' => $id));
    }
    
}