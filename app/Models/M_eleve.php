<?php

namespace App\Models;

use CodeIgniter\Model;

class M_eleve extends Model
{
    protected $table = 'eleve';
    protected $primaryKey = 'id_eleve';

    protected $allowedFields = ['ien_eleve', 'nom_eleve', 'prenom_eleve', 'num_primaire', 'num_secondaire', 'date_naissance', 'sexe', 'email', 'lieu_naissance',
                                'cni_eleve', 'password', 'option', 'numero_table_bac'];

    // public function getUserByNumTel($num_tel, $mdp)
    // {
    //     return $this->where('num_primaire', $num_tel)
    //         ->where('password', $mdp)
    //         ->first();
    // }

    public function getUserByNumTel($num_tel, $mdp)
    {
        $user = $this->where('num_primaire', $num_tel)->first();
        if ($user && password_verify($mdp, $user['password'])) {
            return $user;
        }
    }
}