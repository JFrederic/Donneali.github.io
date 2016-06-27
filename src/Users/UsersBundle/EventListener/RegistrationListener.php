<?php
namespace Users\UsersBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Listener responsible to change the redirection at the end of the password resetting
 */
class RegistrationListener implements EventSubscriberInterface
{

  private $router;

public function __construct(UrlGeneratorInterface $router)
{
    $this->router = $router;
}
    /**
     * {@inheritDoc}
     */


    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess',
        );
    }

    public function onRegistrationSuccess(FormEvent $event)
    {





        $rolesParticulier = 'ROLE_PARTICULIER';
        $rolesAssos = 'ROLE_ASSOS';

       /** @var $user \FOS\UserBundle\Model\UserInterface */
       $user = $event->getForm()->getData();

       if($user->getType() == 1)
       {
         $user->addRole($rolesAssos);
       }
       else {
         $user->addRole($rolesParticulier);
       }

       $url = $this->router->generate('homepage');

          $event->setResponse(new RedirectResponse($url));

    }

}
