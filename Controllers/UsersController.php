<?php

    namespace Controllers;

    use Core\Controller;
    use Models\User;

    class UsersController extends Controller
    {
        public function index()
        {
            
        }

        public function login()
        {
            $response = [
                'error' => false
            ];

            $method = $this->getMethod();
            $data = $this->getRequestData();

            if ($method == 'POST') {
                if (!empty($data['email']) && !empty($data['password'])) {
                    $user = new User();

                    if ($user->checkCredentials($data['email'], $data['password'])) {
                        // Gerar JWT
                        $response['jwt'] = $user->createJWT();
                    } else {
                        $response['error'] = 'Acesso negado.';
                    }
                } else {
                    $response['error'] = 'E-mail e/ou senha não preenchidos.';
                }
            } else {
                $response['error'] = 'Método de requisição incompatível.';
            }

            return $this->returnJson($response);
        }

        public function register()
        {
            $response = [
                'error' => false
            ];

            $method = $this->getMethod();
            $data = $this->getRequestData();

            if ($method == 'POST') {
                if ($this->validateFields($data, ['name', 'email', 'password'])) {
                    if (filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                        $user = new User();

                        if ($user->create($data)) {
                            $response['jwt'] = $user->createJWT();
                        } else {
                            $response['error'] = 'E-mail já existente.';
                        }
                    } else {
                        $response['error'] = 'E-mail inválido.';
                    }
                } else {
                    $response['error'] = 'Dados não preenchidos.';
                }
            } else {
                $response['error'] = 'Método de requisição incompatível.';
            }

            return $this->returnJson($response);
        }
    }
