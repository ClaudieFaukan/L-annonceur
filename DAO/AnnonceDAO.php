<?php

    interface AnnonceDAO
    {
        public function insert(Annonce $a);

        public function delete(Annonce $a);

        public function update(Annonce $annonce);

        public function getByRubrique(Rubrique $rubrique);

        public function getByUser(Utilisateur $utilisateur);

        public function getByAnnonce(string $id_annonce);

        public function deletePerimees();
    }
