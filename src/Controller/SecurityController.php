<?php

namespace App\Controller;

use App\Entity\User;
use App\Event\UserEvent;
use App\Entity\PasswordReset;
use App\Form\PasswordResetType;
use App\Repository\UserRepository;
use App\Security\Link\GenerateLink;
use App\Repository\PasswordResetRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * This is the route the user can use to logout.
     *
     * But, this will never be executed. Symfony will intercept this first
     * and handle the logout automatically. See logout in config/packages/security.yaml
     *
     * @Route("/logout", name="security_logout")
     */
    public function logout(): void
    {
        // throw new \Exception('This should never be reached!');
    }

      /**
     * @Route("/login/reset-password", name="resetpassword")
     */
    public function resetPassword(Request $request,TranslatorInterface $translator, UserRepository $UserRepositroy, \Swift_Mailer $mailer, EventDispatcherInterface $dispatcher, GenerateLink $glink)
    {
        // Check if user is logged and redirect to home page
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('app_document_new');
        }

        $PasswordReset = new PasswordReset();
        $form = $this->createForm(PasswordResetType::class, $PasswordReset);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form['email']->getData();
            $protocol = $request->isSecure() ? 'https://' : 'http://';
            $locale = $request->getLocale();
            $hostName = $protocol.$request->getHttpHost()."/".$locale; 
            $resetUrl = $glink->generateResetPasswordLink($hostName, $email, 'PT01H');
            
            if ($resetUrl) { 
                $user = $UserRepositroy->findOneBy([
                    'email'=>$email
                    ]
                );
 
             $mailContent = [
                'subject' => 'Communuty: Reset password',
                'fromEmail' => 'community@comdesk.com',
                'fromName' => 'Exchanges ideas ! ',
                'toEmail' => $user->getEmail(),
                'toName' => $user->getFirstname() . ' ' . $user->getLastname(),
                'view' => $this->renderView(
                    'email/resetPassword.html.twig',
                    ['url' => $resetUrl, 'username' => $user->getFirstname() . ' ' . $user->getLastname()]
                ),
            ];
 
            //Création de l'événement new user
            $event = new UserEvent($user, $mailContent);
            $dispatcher->dispatch(UserEvent::EMAIL_RESET_PASSWORD, $event);
            $message = $translator->trans('flash.email.reset.sent'); 
            $this->addFlash('success', "{$message} : " . $user->getEmail());
              return $this->redirectToRoute('app_login');
            } else { 
                $message = $translator->trans('flash.not.exist.email');
                $this->addFlash('warning', "Email {$email} {$message} ");
            }
        }
        return $this->render(
            'security/resetPassword.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * @Route("/login/reset-password-checker", name="resetpasswordchecker")
     */
    public function passwordResetCheck(Request $request, UserRepository $userRepos, PasswordResetRepository $passwordRepos, UserPasswordEncoderInterface $encoder) :Response
    {
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('homepage');
        }  
        $em = $this->getDoctrine()->getManager();
        //Verification of email and selector and token
        $selector = $request->query->get('selector');
        $token = $request->query->get('validator');
        $PasswordReset = new PasswordReset();
        $form = $this->createForm(PasswordResetType::class, $PasswordReset);
        $form->handleRequest($request);

        $resetPasswordObjet = $passwordRepos->findOneBy([
            'selector' => $selector, 
            'token' => $token
            ]);
        if (count((array)$resetPasswordObjet) > 0) {
            $dataTimeNow = new \DateTime('NOW');
            if ($resetPasswordObjet->getExpires() > $dataTimeNow) {
                $data = array('selector' => $selector, 'token' => $token);
                if ($form->isSubmitted() && $form->isValid()) {
                    $user = $userRepos->findOneBy([
                        'email' => $resetPasswordObjet->getEmail()
                        ]);
                    $newPassword = $form['newPassword']->getData();
                    // Get Current user object

                    $user->setPassword($encoder->encodePassword($user, $form->get('newPassword')->getData()));
   
                    $em->persist($user);
                    $em->remove($resetPasswordObjet);
                    $em->flush();
                    $this->addFlash('success', "Votre mote de passe a été changé avec succès");
                    return $this->redirectToRoute('app_login');
                }
                return $this->render(
                    'security/resetPassword.html.twig',
                    array('data' => $data, 'form' => $form->createView())
                );
            } else {
                $this->addFlash('warning', "Lien expiré");
            }
        } else {
            $this->addFlash('warning', "Ce lien n'est plus valable");
        }
        return $this->redirectToRoute('app_login');
    }
}
