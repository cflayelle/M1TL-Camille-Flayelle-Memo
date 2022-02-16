<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Form\SearchType;
use App\Entity\Memo;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class MemoController extends AbstractController
{

    /**
     * @Route("/new",name="memo.add", methods={"GET","POST"});
     *
     * @return Response
     */
    public function new(Request $request) : Response 
    {
        $memo = new Memo();
        $memo->setContenu($this->security->getUser());
        $memo->setCreatAt(new DateTimeImmutable());
        $form = $this->createForm(MemoType::class, $memo);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($memo);
            $entityManager->flush();

            $this->addFlash('success', 'Le memo a été créer avec succès 👍.');
            return $this->redirectToRoute('memo');
        }

        return  $this->render('memo/new.html.twig',[
            'memo'=>$memo,
            'form'=>$form->createView()
        ]);
    }



    #[Route('/memo', name: 'memo')]
    public function index(): Response
    {
        return $this->render('memo/index.html.twig', [
            'controller_name' => 'MemoController',
        ]);
    }
}
