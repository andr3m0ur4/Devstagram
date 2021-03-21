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
                'error' => ''
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
                'error' => ''
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

        public function view($id)
        {
            $response = [
                'error' => '',
                'logged' => false
            ];

            $method = $this->getMethod();
            $data = $this->getRequestData();
            $token = $_SERVER['HTTP_JWT'];

            $user = new User();

            if (!empty($token) && $user->validateJWT($token)) {
                $response['logged'] = true;
                $response['thats_me'] = false;

                if ($id == $user->getId()) {
                    $response['thats_me'] = true;
                }

                switch ($method) {
                    case 'GET':
                        $response['data'] = $user->getUser($id);

                        if (count((array) $response['data']) === 0) {
                            $response['error'] = 'Usuário não existe.';
                        }
                        break;

                    case 'PUT':
                        break;

                    case 'DELETE':
                        break;

                    default:
                        $response['error'] = "Método $method não disponível.";
                }
            } else {
                $response['error'] = 'Acesso negado.';
            }

            return $this->returnJson($response);
        }
    }
