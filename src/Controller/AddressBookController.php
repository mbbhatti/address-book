<?php

namespace App\Controller;

use App\Service\AddressService;
use App\Repository\AddressBookRepository;
use App\Service\FileService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
use App\Service\PaginationService;

class AddressBookController extends Controller
{
    /**
     * @Route("/", name="address_list", methods="GET")
     *
     * @param Request $request
     * @param PaginationService $pagination
     * @return Response
     */
    public function list(Request $request, PaginationService $pagination): Response
    {
        $addresses = $pagination->paginate($request);

        return $this->render('address_book/list.html.twig', [
            'addresses' => $addresses,
            'lastPage' => $pagination->lastPage($addresses)
        ]);
    }

    /**
     * @Route("/create", name="address_create", methods={"GET","POST"})
     *
     * @param Request $request
     * @param AddressService $address
     * @return Response
     */
    public function create(Request $request, AddressService $address): Response
    {
        if ($request->isMethod('POST')) {
            if ($address->insertUpdate($request)) {
                $this->addFlash('success', 'Address has been saved successfully');
            } else {
                $this->addFlash('error', 'Address not saved');
            }

            return $this->redirectToRoute('address_list');
        }

        return $this->render('address_book/create.html.twig');
    }

    /**
     * @Route("/update/{id}", name="address_update", methods={"GET","PUT"}, requirements={"id"="\d+"})
     *
     * @param Request $request
     * @param int $id
     * @param AddressBookRepository $addressBookRepository
     * @param AddressService $addressService
     * @return Response
     */
    public function update(
        Request $request,
        int $id,
        AddressBookRepository $addressBookRepository,
        AddressService $addressService
    ): Response
    {
        if ($request->isMethod('PUT')) {
            if ($addressService->insertUpdate($request, $id)) {
                $this->addFlash('success', 'Address updated successfully');
            } else {
                $this->addFlash('error', 'Address not updated');
            }

            return $this->redirectToRoute('address_list');
        }

        $address = $addressBookRepository->find($id);
        if ($address === null) {
            $this->addFlash('error', 'Address not found');

            return $this->redirectToRoute('address_list');
        }

        return $this->render('address_book/update.html.twig', [
            'address' => $address
        ]);
    }

    /**
     * @Route("/delete/{id}", name="address_delete", requirements={"id"="\d+"})
     * @Method("DELETE")
     *
     * @param int $id
     * @param AddressBookRepository $addressBookRepository
     * @param FileService $fileService
     * @return RedirectResponse
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(int $id, AddressBookRepository $addressBookRepository, FileService $fileService)
    {
        $file = $addressBookRepository->deleteById($id);
        if ($file === false) {
            $this->addFlash('error', 'Address not found');
        } else {
            if (isset($file)) {
                $fileService->remove($file);
            }
            $this->addFlash('success', 'Address removed successfully');
        }

        return $this->redirectToRoute('address_list');
    }
}

