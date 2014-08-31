<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 31/08/2014
 * Time: 18:02
 */

namespace JDJ\UserReviewBundle\Controller;


use JDJ\JeuBundle\Entity\Jeu;
use JDJ\UserReviewBundle\Entity\JeuNote;
use JDJ\UserReviewBundle\Entity\Note;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class JeuNoteController extends Controller
{
    /**
     * Find Jeu Entity
     *
     * @param $idJeu
     * @return Jeu
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    protected function findJeu($idJeu)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Jeu $jeu */
        $jeu = $em->getRepository('JDJJeuBundle:Jeu')->find($idJeu);

        if (!$jeu) {
            throw $this->createNotFoundException('Unable to find Jeu entity.');
        }

        return $jeu;
    }

    /**
     * @param Jeu $jeu
     * @return mixed
     */
    protected function findJeuNote(Jeu $jeu)
    {
        $em = $this->getDoctrine()->getManager();
        return $em->getRepository('JDJUserReviewBundle:JeuNote')->findOneBy(array(
            'jeu' => $jeu,
            'author'=> $this->getUser(),
        ));
    }

    /**
     * @param $idNote
     * @return Note
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    protected function findNote($idNote)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Note $note */

        $note = $em->getRepository('JDJJeuBundle:Jeu')->find($idNote);

        if (!$note) {
            throw $this->createNotFoundException('Unable to find Note entity.');
        }

        return $note;
    }

    /**
     * Creates a new JeuNote entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new JeuNote();

        $jeu = $this->findJeu($request->request->get("idJeu"));
        $note = $this->findNote($request->request->get("idNote"));

        $entity
            ->setJeu($jeu)
            ->setNote($note)
            ->setAuthor($this->getUser())
        ;

        $em = $this->getDoctrine()->getManager();

        $em->persist($entity);
        $em->flush();
    }

    /**
     * Displays a form to create a new JeuNote entity.
     *
     */
    public function newAction($idJeu)
    {
        $jeu = $this->findJeu($idJeu);
        $em = $this->getDoctrine()->getManager();
        $userNote = $em->getRepository('JDJUserReviewBundle:JeuNote')->findOneBy(array(
            'jeu' => $jeu,
            'author' => $this->getUser(),
        ));
        $notes = $em->getRepository('JDJUserReviewBundle:Note')->findAll();

        return $this->render('JDJUserReviewBundle:JeuNote:new.html.twig', array(
            'userNote' => $userNote,
            'notes' => $notes,
        ));
    }
} 