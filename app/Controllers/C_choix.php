<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\M_choix;

class C_choix extends BaseController
{

    public function saveChoices()
    {
        $data = $this->request->getJSON(true);
        // log_message('debug', 'Requête reçue : ' . print_r($data, true));
        $choix_model = new M_choix();

        foreach ($data as $item) {
            $existe = $choix_model
                ->where('id_eleve', $item['id_eleve'])
                ->where('code_ief', $item['code_ief'])
                ->first();

            if ($existe) {
                $choix_model->update($existe['id_choix'], [
                    'rang' => $item['rang']
                ]);
            } else {
                $choix_model->insert([
                    'id_eleve' => $item['id_eleve'],
                    'code_ief' => $item['code_ief'],
                    'rang' => $item['rang'],
                ]);
            }
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Choix enregistrés avec succès !'
        ]);
    }

    public function getChoicesByEleve($id_eleve)
    {
        $choix_model = new M_choix();

        $choix = $choix_model   
            ->where('id_eleve', $id_eleve)
            ->orderBy('rang', 'ASC')
            ->findAll();

        return $this->response->setJSON([
            'status' => 'success',
            'choix' => $choix
        ]);
    }
}