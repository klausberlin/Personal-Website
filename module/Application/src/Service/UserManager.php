<?php
/**
 * Created by PhpStorm.
 * User: localklaus
 * Date: 07.10.18
 * Time: 18:03
 */

namespace Application\Service;


use Application\Entity\User;
use Zend\Crypt\Password\Bcrypt;

use Zend\Mail;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Math\Rand;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;


class UserManager
{
    /**
     * Doctrine entity manager.
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * PHP template renderer.
     * @var type
     */
    private $viewRenderer;


    /**
     * UserManager constructor.
     * @param $entityManager
     */
    public function __construct($entityManager, $viewRenderer)
    {
        $this->entityManager = $entityManager;
        $this->viewRenderer = $viewRenderer;
    }


    /**
     * This method adds a new user.
     * @throws \Exception
     */
    public function addUser($data)
    {
        // Do not allow several users with the same email address.
        if($this->checkUserExists($data['email'])) {
            throw new \Exception("User with email address " . $data['$email'] . " already exists");
        }

        // Create new User entity.
        $user = new User();
        $user->setEmail($data['email']);
        $user->setFullName($data['full_name']);
        // Encrypt password and store the password in encrypted state.
        //$bcrypt = new Bcrypt();
        $passwordHash = sha1($data['password']);
        $user->setPassword($passwordHash);

        $user->setStatus($data['status']);

        $currentDate = date('Y-m-d H:i:s');
        $user->setDateCreated($currentDate);

        // Add the entity to the entity manager.
        $this->entityManager->persist($user);

        // Apply changes to database.
        $this->entityManager->flush();

        return $user;
    }



    /**
     * This method checks if at least one user presents, and if not, creates
     * 'Admin' user with email 'admin@example.com' and password 'Secur1ty'.
     */
    public function createAdminUserIfNotExists()
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy([]);
        if ($user==null) {

            $user = new User();
            $user->setEmail('admin@example.com');
            $user->setFullName('Admin');
            //$bcrypt = new Bcrypt();
            $passwordHash = sha1('Secur1ty');
            $user->setPassword($passwordHash);
            $user->setStatus(User::STATUS_ACTIVE);
            $user->setDateCreated(date('Y-m-d H:i:s'));

            $this->entityManager->persist($user);

            $this->entityManager->flush();
        }
    }



    /**
     * Checks whether an active user with given email address already exists in the database.
     */
    public function checkUserExists($email) {

        $user = $this->entityManager->getRepository(User::class)
            ->findOneByEmail($email);

        return $user !== null;
    }


    /**
     * Generates a password reset token for the user. This token is then stored in database and
     * sent to the user's E-mail address. When the user clicks the link in E-mail message, he is
     * directed to the Set Password page.
     * @throws \Exception
     */
    public function generatePasswordResetToken($user)
    {
        if ($user->getStatus() != User::STATUS_ACTIVE) {
            throw new \Exception('Cannot generate password reset token for inactive user ' . $user->getEmail());
        }

        // Generate a token.
        $token = Rand::getString(32, '0123456789abcdefghijklmnopqrstuvwxyz', true);

        // Encrypt the token before storing it in DB.
        $bcrypt = new Bcrypt();
        $tokenHash = $bcrypt->create($token);

        // Save token to DB
        $user->setPasswordResetToken($tokenHash);

        // Save token creation date to DB.
        $currentDate = date('Y-m-d H:i:s');
        $user->setPasswordResetTokenCreationDate($currentDate);

        // Apply changes to DB.
        $this->entityManager->flush();

        $httpHost = isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:'localhost';
        $passwordResetUrl = 'http://' . $httpHost . '/set-password?token=' . $token . "&email=" . $user->getEmail();

        // Produce HTML of password reset email
        $bodyHtml = $this->viewRenderer->render(
            'application/user/email/reset-password-email',
            [
                'passwordResetUrl' => $passwordResetUrl,
            ]);

        $html = new MimePart($bodyHtml);
        $html->type = "text/html";

        $body = new MimeMessage();
        $body->addPart($html);


        $email = new \SendGrid\Mail\Mail;
        $email->setFrom('mrpawelklaus@gmail.com', 'User Demo');
        $email->setSubject("Password Reset");
        $email->addTo($user->getEmail(), $user->getFullName());
        $email->addContent("text/html", $bodyHtml);


        //todo set to env var
        $sendgrid = new \SendGrid('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');

        try {

            $response = $sendgrid->send($email);
            print $response->statusCode() . "\n";
            print_r($response->headers());
            print $response->body() . "\n";
            print $user->getEmail() . "\n";
            print $user->getFullName(). "\n";

        } catch (Exception $e) {

            echo 'Caught exception: '. $e->getMessage() ."\n";

        }
    }

    /**
     * Checks whether the given password reset token is a valid one.
     */
    public function validatePasswordResetToken($email, $passwordResetToken)
    {
        // Find user by email.
        $user = $this->entityManager->getRepository(User::class)
            ->findOneByEmail($email);

        if($user==null || $user->getStatus() != User::STATUS_ACTIVE) {
            return false;
        }

        //TODO exmine token with database
        // Check that token hash matches the token hash in our DB.
//        $bcrypt = new Bcrypt();
        $tokenHash = $user->getResetPasswordToken();

//        if (!sha1($passwordResetToken, $tokenHash)) {
//            return false; // mismatch
//        }

        // Check that token was created not too long ago.
        $tokenCreationDate = $user->getPasswordResetTokenCreationDate();
        $tokenCreationDate = strtotime($tokenCreationDate);

        $currentDate = strtotime('now');

        if ($currentDate - $tokenCreationDate > 24*60*60) {
            return false; // expired
        }

        return true;
    }

    /**
     * This method sets new password by password reset token.
     */
    public function setNewPasswordByToken($email, $passwordResetToken, $newPassword)
    {
        if (!$this->validatePasswordResetToken($email, $passwordResetToken)) {
            return false;
        }

        // Find user with the given email.
        $user = $this->entityManager->getRepository(User::class)
            ->findOneByEmail($email);

        if ($user==null || $user->getStatus() != User::STATUS_ACTIVE) {
            return false;
        }

        // Set new password for user
//        $bcrypt = new Bcrypt();
        $passwordHash = sha1($newPassword);
        $user->setPassword($passwordHash);

        // Remove password reset token
        $user->setPasswordResetToken(null);
        $user->setPasswordResetTokenCreationDate(null);

        $this->entityManager->flush();

        return true;
    }




}