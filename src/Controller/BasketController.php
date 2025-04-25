    #[Route('/add/{id}', name: 'app_basket_add', methods: ['POST'])]
    public function add(
        Document $document,
        EntityManagerInterface $entityManager,
        #[CurrentUser()] User $user,
        BasketManager $basketManager,
    ): RedirectResponse
    {
        // Chercher un exemplaire disponible
        $copy = $entityManager->getRepository(Copy::class)->findOneBy(['document' => $document, 'state' => CopyState::Available]);

        if (!$copy) {
            $this->addFlash('danger', 'Ce document n\'a plus d\'exemplaire disponible.');
            return $this->redirectToRoute('app_document_show', ['id' => $document->getId()]);
        }

        // Vérifier si l'utilisateur a déjà un panier
        $basket = $entityManager->getRepository(Basket::class)->findOneBy(['user' => $user, 'state' => BasketState::Pending]);
        if (!$basket) {
            $basket = new Basket();
            $basketManager->create($user, $basket);
            $entityManager->persist($basket);
        }

        // Vérifier que le panier ne dépasse pas 5 exemplaires
        if ($basket->getCopies()->count() >= 5) {
            $this->addFlash('danger', 'Vous ne pouvez pas ajouter plus de 5 exemplaires à votre panier.');
            return $this->redirectToRoute('app_document_show', ['id' => $document->getId()]);
        }

        $basket->addCopy($copy);
        $entityManager->flush();

        $this->addFlash('success', 'Exemplaire ajouté au panier.');
        return $this->redirectToRoute('app_document_show', ['id' => $document->getId()]);
    }

    #[Route('/remove/{id}', name: 'app_basket_remove', methods: ['POST'])]
    public function remove(
        Copy $copy,
        #[CurrentUser()] User $user,
        EntityManagerInterface $entityManager,
    ): RedirectResponse
    {
        $basket = $entityManager->getRepository(Basket::class)->findOneBy(['user' => $user, 'state' => BasketState::Pending]);

        if (!$basket || !$basket->getCopies()->contains($copy)) {
            $this->addFlash('warning', 'Cette copie n\'est pas dans votre panier.');
            return $this->redirectToRoute('app_basket');
        }

        $basket->removeCopy($copy);
        $entityManager->persist($basket);
        $entityManager->flush();

        $this->addFlash('success', 'Exemplaire retirée du panier.');
        return $this->redirectToRoute('app_basket');
    }

