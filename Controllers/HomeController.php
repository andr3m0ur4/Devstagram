<?php

    namespace Controllers;

    use Core\Controller;
    use Models\Usuario;

    class HomeController extends Controller
    {
        public function index()
        {
            $data = [
                'nome' => 'André Moura',
                'idade' => 30
            ];

            return $this->returnJson($data);
        }
    }
