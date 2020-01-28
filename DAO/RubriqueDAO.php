<?php

    require_once dirname(__DIR__).'/Domain/Rubrique.php';

    interface RubriqueDAO
    {
        public function insert(Rubrique $r);

        public function delete(Rubrique $r);

        public function update(Rubrique $r, $newlibelle);

        public function getAll();
    }
