# CampaignFlow API

API REST para gestão de campanhas políticas e gabinetes de mandato.

Projeto de portfólio técnico focado em demonstrar:
- Desenvolvimento de APIs robustas com Laravel
- Modelagem de banco de dados e práticas de DBA
- Arquitetura limpa e organizada
- Recursos avançados do Laravel (Sanctum, Policies, Events, Jobs, Queues, etc.)
- Otimização de queries e performance
- Comparativo MySQL × PostgreSQL (mesmo schema)

## Stack

- **Laravel** 12
- **PHP** 8.3+
- **MySQL** 8.0+ (schema 100% compatível com PostgreSQL)
- **Laravel Sanctum** (autenticação)
- **Redis** (cache + queues)
- **Docker** (ambiente de desenvolvimento)

## Status atual

Em desenvolvimento ativo — construção **incremental**.

## Roadmap

- [ ] Autenticação + Multi-workspace + Roles & Permissions
- [ ] Módulo de Eleitores (com filtros avançados)
- [ ] Organização hierárquica de equipes
- [ ] Atividades de campo / visitas
- [ ] Demandas do gabinete
- [ ] Agenda de compromissos
- [ ] Controle de doações e gastos
- [ ] Dashboard e relatórios
- [ ] Sistema de auditoria + Soft Deletes
- [ ] Documentação da API (Scribe/Scramble)
- [ ] Testes automatizados
- [ ] Comparativo de performance MySQL × PostgreSQL

## Como rodar o projeto

### Pré-requisitos
- Docker + Docker Compose
- Git

### Subindo o ambiente

```bash
cp .env.example .env
docker compose up -d


Autor
Thierry Matheus

Desenvolvedor Backend & DBA.