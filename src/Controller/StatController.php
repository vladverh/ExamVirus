<?php

namespace App\Controller;

use App\Entity\Stat;
use App\Form\StatType;
use App\Repository\StatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/stat")
 */
class StatController extends AbstractController
{
    /**
     * @Route("/", name="stat_index", methods={"GET"})
     */
    public function index(StatRepository $statRepository): Response
    {
        return $this->render('stat/index.html.twig', [
            'stats' => $statRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="stat_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $stat = new Stat();
        $form = $this->createForm(StatType::class, $stat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($stat);
            $entityManager->flush();

            return $this->redirectToRoute('app_index');
        }

        return $this->render('stat/new.html.twig', [
            'stat' => $stat,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="stat_show", methods={"GET"})
     */
    public function show(Stat $stat): Response
    {
        return $this->render('stat/show.html.twig', [
            'stat' => $stat,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="stat_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Stat $stat): Response
    {
        $form = $this->createForm(StatType::class, $stat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('stat_index');
        }

        return $this->render('stat/edit.html.twig', [
            'stat' => $stat,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="stat_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Stat $stat): Response
    {
        if ($this->isCsrfTokenValid('delete'.$stat->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($stat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('stat_index');
    }
}
