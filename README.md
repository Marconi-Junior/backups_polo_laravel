# Sistema de Monitoramento de Backups e Conexão de Internet

Um sistema web robusto desenvolvido em **Laravel 13** projetado para automatizar a geração de dumps de bancos de dados, armazená-los localmente e na nuvem, além de fornecer ferramentas para testes de latência e conectividade de rede (ping).

---

## 🚀 Funcionalidades Principais

### 🔒 Autenticação e Perfil
* **Acesso Seguro:** Tela de login compatível com usuário ou e-mail e senha.
* **Autogestão:** Opções nativas para cadastro de novos usuários e recuperação de senha ("Esqueci a senha").
* **Perfil do Usuário:** Menu drop-down no canto superior direito para edição dos dados da conta e logout.

### 📊 Dashboard Central
* Interface limpa contendo dois cards direcionais de acesso rápido:
  1. **Gerenciar Backups** (Acesso ao CRUD de instâncias).
  2. **Testar Conexão** (Ferramenta de monitoramento de ping).

### 🗄️ Gerenciamento de Backups (CRUD)
* **Controle Total:** Criação, edição, visualização e exclusão de conexões de bancos de dados.
* **Metadados da Conexão:** Suporte a IP/Host, Porta, Nome do Banco, Usuário e Senha.
* **Agendamento Flexível:** Definição da frequência dos dumps à meia-noite via seletor:
  * Trimestral
  * Mensal
  * Semanal
  * 3x por semana (seg-qua-sex)
* **Armazenamento Híbrido:** Os dumps gerados são salvos localmente no repositório do projeto e uma cópia de segurança é enviada para o **Google Drive**.
* **Alertas Automatizados:** Disparo de notificações por e-mail informando o sucesso ou falha da rotina.

### 🌐 Teste de Conexão (Ping)
* **Disparo sob Demanda:** Botão dedicado para execução imediata do teste de ping.
* **Relatório Dinâmico:** Exibição em tempo real dos resultados técnicos e status diretamente na tela.
* **Configuração Centralizada:** O host de destino do teste é gerenciável diretamente a nível de código para maior segurança estrutural.
* **Canais de Notificação:** Botões integrados para encaminhar o relatório gerado por **E-mail** ou **Telegram**.

---

## 🛠️ Tecnologias Utilizadas

* **Framework:** Laravel 13.x
* **Linguagem:** PHP 8.3+
* **Banco de Dados:** PostgreSQL (compatível com os drivers PDO)
* **Integrações:** Google Drive API (via Flysystem Storage Provider) & Telegram Bot API

---

## 🔧 Instalação e Configuração

### 1. Clonar o Repositório
```bash
git clone https://github.com/seu-usuario/seu-repositorio.git
cd seu-repositorio
```

### 2. Instalar Dependências
```bash
composer install
npm install && npm run dev
```

### 3. Configuração de Ambiente (`.env`)
Copie o arquivo de exemplo e configure suas variáveis:
```bash
cp .env.example .env
```

Abra o arquivo `.env` e configure os parâmetros essenciais:
```env
# Banco de dados do painel
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=nome_do_banco
DB_USERNAME=usuario
DB_PASSWORD=senha

# Configuração de E-mail (SMTP)
MAIL_MAILER=smtp
MAIL_SCHEME=null
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_ENCRYPTION=tls
MAIL_USERNAME="seu-email@gmail.com"
MAIL_PASSWORD="senha_de_app_16_caracteres"
MAIL_FROM_ADDRESS="seu-email@gmail.com"
MAIL_FROM_NAME="nome_banco"

# Integração Google Drive
GOOGLE_DRIVE_CLIENT_ID=seu_client_id
GOOGLE_DRIVE_CLIENT_SECRET=seu_client_secret
GOOGLE_DRIVE_REFRESH_TOKEN=seu_refresh_token
GOOGLE_DRIVE_FOLDER_ID=seu_folder_id
GOOGLE_DRIVE_FOLDER_NAME="/nome_repositorio"

# Integração Telegram
TELEGRAM_BOT_TOKEN=seu_token_do_bot
TELEGRAM_CHAT_ID=seu_chat_id_ou_canal
```

### 4. Inicializar a Aplicação
Configure a chave da aplicação, execute as migrations com as tabelas de usuários e conexões, e popule o banco se necessário:
```bash
php artisan key:generate
php artisan migrate
```

---

## ⏰ Cron e Agendamentos (Task Scheduling)

Para que as rotinas de dump à meia-noite funcionem corretamente, certifique-se de que o Scheduler do Laravel está ativo no Cron do seu servidor:

```bash
* * * * * cd /caminho-do-seu-projeto && php artisan schedule:run >> /dev/null 2>&1
```

O mapeamento interno em `routes/console.php` (ou na classe `Kernel`) processará de forma transparente os agendamentos cadastrados (trimestral, mensal, semanal ou 3x na semana) garantindo a execução pontual à **00:00**.
