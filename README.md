# Full Stack Developer Test - DFranquias

### Candidato: Felipe Mendes

### O que foi utilizado?
- Framework Symfony
- Docker
- MySQL (MariaDB)
- Bootstrap
- KnpLabs/KnpPaginatorBundle 

### Qual o objetivo?
- Utilize o PHP e preferencialmente os frameworks Symfony ou Laravel para desenvolver um sistema que auxilie no controle de uma fazenda de bovinos.

### Requisitos Funcionais

![Captura de tela 2025-08-19 023857](https://github.com/user-attachments/assets/d2bda8dd-9b89-431b-a443-180359577595)

- CRUD do veterinário, manipulando os seguintes dados:
  - Nome: nome do veterinário.
  - CRMV: código do veterinário.

- CRUD da fazenda, manipulando os seguintes dados:
  - Nome: nome da fazenda.
  - Tamanho: Tamanho da fazenda em hectares (HA).
  - Responsável: Nome do responsável pela fazenda.
  - Veterinários: Uma fazenda pode ter um ou vários veterinários(ManyToMany)

- CRUD do gado da fazenda, manipulando os seguintes dados:
  - Código: código da cabeça de gado;
  - Leite: número de litros de leite produzido por semana;
  - Ração: quantidade de alimento ingerida por semana - em quilos;
  - Peso: peso do animal em quilos;
  - Nascimento: data de nascimento do animal;
  - Fazenda: fazenda a que o gado pertence (ManyToOne).

  ### Relatórios
- Relatório de animais abatidos;
- Relatório da quantidade total de leite produzido por semana (Tela inicial);
- Relatório da quantidade total de ração necessária por semana (Tela inicial);
- Relatório da quantidade total de animais que tenham até 1 ano de idade e que consumam mais de 500Kg de ração por semana (Tela inicial).

### Requisitos de sistema
- Adicionar ao projeto, o pacote KnpLabs/KnpPaginatorBundle, para paginação e ordenação dos registros em tela;
- Usar as flash messages do próprio framework para notificações dentro do sistema, dando um feedback das ações realizadas, melhorando a experiência do usuário;
- Criar funções customizadas no repository para buscas mais elaboradas no BD.

### Imagens
![Captura de tela 2025-08-19 023103](https://github.com/user-attachments/assets/2af4b49d-13a9-4d14-94d7-5e45c31cdd5f)
![Captura de tela 2025-08-19 023310](https://github.com/user-attachments/assets/0e26c380-4663-4700-84e5-e054177d687a)
![Captura de tela 2025-08-19 023033](https://github.com/user-attachments/assets/c4fa7f2c-30f8-40a5-b623-97a395f445dd)

