
Markdown
# KiFacil

## Sistema Georreferenciado de Matchmaking e Distribuição Assíncrona de Vagas de Estágio

O KiFacil é uma plataforma de engenharia de software desenhada para descentralizar e otimizar o processo de inserção de estudantes finalistas do ensino técnico e superior no mercado de trabalho. O sistema combina mapas interativos com um motor algorítmico baseado na Fórmula de Haversine para efetuar o cruzamento (*matchmaking*) em tempo real entre o raio de atuação definido pelas empresas e as coordenadas geográficas dos estudantes, mitigando barreiras logísticas e custos de deslocação.

---

## Tecnologias e Ferramentas

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Leaflet](https://img.shields.io/badge/Leaflet-199900?style=for-the-badge&logo=leaflet&logoColor=white)
![Git](https://img.shields.io/badge/GIT-E44D26?style=for-the-badge&logo=git&logoColor=white)

---

## Arquitetura e Escopo do Sistema

O ecossistema opera sob o padrão arquitetónico **MVC (Model-View-Controller)** e subdivide-se em três macro-interfaces integradas sobre uma infraestrutura unificada:

*   **Área Administrativa (Backoffice):** Centraliza a monitorização do ciclo de vida dos estudantes, a validação jurídica de alvarás comerciais de contratantes e o controlo de logs de auditoria securitária global.
*   **Área do Candidato (Estudante):** Interface reativa com mapa georreferenciado (Leaflet/OpenStreetMap), módulo dinâmico de gestão de valências técnicas por delimitadores e mecanismo *drag-and-drop* para submissão assíncrona de arquivos PDF.
*   **Área Corporativa (Empresa):** Painel focado na captura de coordenadas espaciais por clique em mapa nativo, parametrização de oportunidades com raios de ação customizáveis (padrão de 15 KM) e captação de candidaturas.

---

## Funcionalidades Principais

*   **Autenticação Segregada:** Controlo de acesso baseado em papéis (RBAC) para Administrador, Estudante e Empresa.
*   **Motor de Matchmaking Geográfico:** Cálculo trigonométrico espacial em backend para cruzar o radar do candidato com o perímetro da vaga.
*   **Fila de Upload Assíncrono:** Processamento em segundo plano para envio de currículos em PDF diretamente para o e-mail central da empresa, evitando bloqueios na interface do utilizador.
*   **Recrutamento às Cegas:** Mascaramento automático de dados sensíveis de contacto do aluno até que a interação ou submissão direta seja formalizada.
*   **Logs de Auditoria Imutáveis:** Registo detalhado de operações críticas (padrão *Write Once, Read Many*) com suporte a exportação em formato `.CSV`.

---

## Requisitos de Sistema e Instalação

### Pré-requisitos
* PHP 8.x ou superior
* Composer
* MySQL 8.0 ou superior

### Passos para Configuração Local

1. Clone o repositório:
bash
   git clone [https://github.com/EdiLucas/kifacil.git](https://github.com/EdiLucas/kifacil.git)
   cd kifacil
Instale as dependências do projeto:

Bash
   composer install
Configure o arquivo de ambiente:

Bash
   cp .env.example .env
Abra o arquivo .env e configure as credenciais da sua base de dados MySQL e o driver de filas (QUEUE_CONNECTION).

Gere a chave da aplicação:

Bash
   php artisan key:generate
Execute as migrações para estruturar o banco de dados (incluindo os índices espaciais):

Bash
   php artisan migrate
Inicie o servidor de desenvolvimento local:

Bash
   php artisan serve
Em outro terminal, inicie o worker para processar as filas assíncronas de e-mails:

Bash
   php artisan queue:work
