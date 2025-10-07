<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\M_eleve;
use CodeIgniter\Debug\Toolbar\Collectors\BaseCollector;

class C_eleve extends BaseController
{

    // public function listUser()
    // {
    //     $usermod = new M_eleve();
    //     $donnee = $usermod->findAll();
    //     // return view('base_url', $donnee);
    //     return $this->response->setJSON($donnee);
    // }

    public function login()
    {
        $userMod = new M_eleve();

        // Récupérer données JSON
        $data = $this->request->getJSON(true);

        $num_tel = $data['num_primaire'];
        $mdp = $data['password'];

        $user = $userMod->getUserByNumTel($num_tel, $mdp);
        // var_dump($user);

        if ($user) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Connexion réussie',
                'user' => $user
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Identifiants invalides'
            ])->setStatusCode(401);
        }
    }

    public function logout()
    {
        session()->destroy();

    }


}