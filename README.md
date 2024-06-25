# Patient Mover

## Visão Geral

O Patient Mover foi desenvolvido para otimizar e gerenciar o processo de transporte de pacientes dentro do hospital. O sistema possui duas interfaces principais: uma para administradores e outra para maqueiros, permitindo uma gestão eficiente e transparente dos transportes.

## Links Importantes

- **Manual do Usuário:** <a href="https://drive.google.com/file/d/18QPZK-XKLEtNUhF2bNAcXFahJoPF6iy-/view?usp=sharing" target="_blank">Link para o Manual do Usuário</a>
- **Relatório de Teste:** <a href="https://drive.google.com/file/d/1rr7R7uyDFVEAmB3DmiWpBebgkzdaWept/view?usp=sharing" target="_blank">Link para o Relatório de Teste</a>

## Teste de Usuário

### Administrador
- **Email:** angelamarques@mailto.plus
- **Senha:** 123456789

### Maqueiro
- **Email:** anamaria@mailto.plus
- **Senha:** anamaria

## Tecnologias Utilizadas

- **Frontend:** Vue.js
- **Backend:** Laravel (PHP)
- **Banco de Dados:** MySQL
- **Servidor Web:** Apache
- **Hospedagem:** AWS EC2 e Vercel

## Funcionalidades

### Para Administradores:
- **Dashboard:** Visualização de estatísticas gerais.
- **Solicitação de Transporte:** Solicitação e gerenciamento de transportes.
- **Gerenciamento de Maqueiros:** Adicionar, ativar, inativar e editar informações de maqueiros.
- **Visualização de Transportes:** Acesso a todos os transportes solicitados, em andamento e concluídos.

### Para Maqueiros:
- **Dashboard:** Visualização de transportes atribuídos.
- **Aceitação e Recusa de Transportes:** Possibilidade de aceitar ou recusar transportes.
- **Início e Conclusão de Transportes:** Funções para iniciar e concluir transportes.
- **Registro de Incidentes:** Registro de quaisquer incidentes ocorridos durante o transporte.

## Instalação

### Pré-requisitos

- PHP 8.1+
- Composer
- MySQL
- Apache

## Instalação

### Backend (Laravel)

1. Navegue até o diretório do backend:
    ```sh
    cd /path/to/Pacient-Mover/backend
    ```

2. Instale as dependências:
    ```sh
    composer install
    ```

3. Copie o arquivo de exemplo `.env` e configure suas variáveis de ambiente:
    ```sh
    cp .env.example .env
    php artisan key:generate
    ```

4. Edite o arquivo `.env` e configure suas credenciais do banco de dados:
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=nome_do_seu_banco_de_dados
    DB_USERNAME=seu_usuario
    DB_PASSWORD=sua_senha
    ```

5. Execute as migrações do banco de dados:
    ```sh
    php artisan migrate
    ```

6. Inicie o servidor:
    ```sh
    php artisan serve
    ```

### Frontend (Vue.js)

1. Navegue até o diretório do frontend:
    ```sh
    cd /path/to/Pacient-Mover/frontend
    ```

2. Instale as dependências:
    ```sh
    npm install
    ```

3. Inicie o servidor de desenvolvimento:
    ```sh
    npm run serve
    ```

### Configuração

1. No frontend, configure a URL do backend no arquivo apiService.js:
    ```apiService
    const baseURL = "http://localhost:8000/api";
    ```

## Uso

### Administradores

1. Faça login com suas credenciais de administrador.
2. Acesse o dashboard para visualizar estatísticas e gráficos.
3. Use a seção de maqueiros para gerenciar os maqueiros.
4. Solicite e gerencie transportes conforme necessário.

### Maqueiros

1. Faça login com suas credenciais de maqueiro.
2. Acesse o dashboard para visualizar os transportes atribuídos a você.
3. Aceite, recuse, inicie e conclua transportes conforme necessário.
4. Registre incidentes se necessário.

## Contribuição

1. Faça um fork do projeto.
2. Crie uma nova branch (`git checkout -b feature/nova-funcionalidade`).
3. Faça commit das suas alterações (`git commit -am 'Adiciona nova funcionalidade'`).
4. Envie para o branch (`git push origin feature/nova-funcionalidade`).
5. Crie um novo Pull Request.

## Licença

Este projeto está licenciado sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.
