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
