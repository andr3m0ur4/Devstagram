users/login (POST)                  = logar usuário
users/new (POST)                    = adicionar um novo usário
users/{id} (GET)                    = informações do usuário específico
users/{id} (PUT)                    = editar usuário específico
users/{id} (DELETE)                 = excluir usuário específico
users/{id}/feed (GET)               = feed de fotos do usuário específico
users/{id}/photos (GET)             = fotos do usuário específico
users/{id}/follow (POST)            = seguir usuário específico
users/{id}/unfollow (DELETE)        = deixar de seguir usuário específico

photos/random (GET)                 = fotos aleatórias
photos/new (POST)                   = inserir nova foto
photos/{id} (GET)                   = informações sobre a foto específica
photos/{id} (DELETE)                = excluir a foto específica
photos/{id}/comment (POST)          = inserir novo comentário na foto específica
photos/{id}/comment (DELETE)        = excluir o comentário na foto específica
photos/{id}/like (POST)             = curtir a foto específica
photos/{id}/dislike (DELETE)        = descurtir a foto específica
