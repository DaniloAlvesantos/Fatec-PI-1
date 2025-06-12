-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Jun 04, 2025 at 04:46 PM
-- Server version: 8.0.40
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pi_fatec`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_chamada`
--

CREATE TABLE `tb_chamada` (
  `id_chamada` int NOT NULL,
  `id_hae` int DEFAULT NULL,
  `id_inscricao` int DEFAULT NULL,
  `data_envio` datetime NOT NULL,
  `quant_hae` int NOT NULL,
  `status` enum('Deferido','Indeferido') NOT NULL DEFAULT 'Deferido',
  `justificativa` text,
  `num_chamada` int NOT NULL,
  `semestre` enum('1','2') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_docente`
--

CREATE TABLE `tb_docente` (
  `id_docente` int NOT NULL,
  `nome` varchar(60) NOT NULL,
  `RG` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(100) NOT NULL,
  `matricula` int NOT NULL,
  `turno` varchar(8) NOT NULL,
  `senha` varchar(25) NOT NULL,
  `cargo` enum('Professor','Coordenador','Secretaria','Diretor') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `outras_fatecs` int DEFAULT NULL,
  `curso` varchar(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_feedback`
--

CREATE TABLE `tb_feedback` (
  `id_feedback` int NOT NULL,
  `id_inscricao` int NOT NULL,
  `data_envio` datetime DEFAULT CURRENT_TIMESTAMP,
  `resultado` enum('Aprovada','Reprovada') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_feedback_comentario`
--

CREATE TABLE `tb_feedback_comentario` (
  `id_comentario` int NOT NULL,
  `id_feedback` int NOT NULL,
  `cargo` enum('Coordenador','Diretor') NOT NULL,
  `id_docente` int NOT NULL,
  `comentario` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_hae`
--

CREATE TABLE `tb_hae` (
  `id_hae` int NOT NULL,
  `titulo` varchar(60) NOT NULL,
  `tip_hae` varchar(30) NOT NULL,
  `quant_hae` int NOT NULL,
  `descricao` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `data_inicio` date NOT NULL,
  `data_final` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_inscricao`
--

CREATE TABLE `tb_inscricao` (
  `id_inscricao` int NOT NULL,
  `id_docente` int DEFAULT NULL,
  `id_hae` int DEFAULT NULL,
  `data_envio` datetime NOT NULL,
  `quant_hae` int DEFAULT NULL,
  `outras_fatecs` int DEFAULT NULL,
  `id_projeto` int DEFAULT NULL,
  `status` enum('Pendente','Em análise','Aprovada','Reprovada') NOT NULL DEFAULT 'Pendente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_projeto`
--

CREATE TABLE `tb_projeto` (
  `id_projeto` int NOT NULL,
  `data_inicio` date NOT NULL,
  `data_final` date NOT NULL,
  `titulo` varchar(40) NOT NULL,
  `id_hae` int DEFAULT NULL,
  `descricoes` json DEFAULT NULL,
  `dias_exec` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_relatorio`
--

CREATE TABLE `tb_relatorio` (
  `id_relatorio` int NOT NULL,
  `id_projeto` int DEFAULT NULL,
  `data_entrega` datetime DEFAULT NULL,
  `pdf_url` text,
  `id_feedback` int DEFAULT NULL,
  `descricoes` json DEFAULT NULL,
  `pdf_nome` varchar(100) DEFAULT NULL,
  `pdf_original_nome` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_relatorio_feedback`
--

CREATE TABLE `tb_relatorio_feedback` (
  `id_relatorio_feedback` int NOT NULL,
  `id_relatorio` int NOT NULL,
  `id_feedback` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_chamada`
--
ALTER TABLE `tb_chamada`
  ADD PRIMARY KEY (`id_chamada`),
  ADD UNIQUE KEY `uq_hae_inscricao` (`id_hae`,`id_inscricao`),
  ADD KEY `id_inscricao` (`id_inscricao`);

--
-- Indexes for table `tb_docente`
--
ALTER TABLE `tb_docente`
  ADD PRIMARY KEY (`id_docente`),
  ADD UNIQUE KEY `RG` (`RG`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `matricula` (`matricula`);

--
-- Indexes for table `tb_feedback`
--
ALTER TABLE `tb_feedback`
  ADD PRIMARY KEY (`id_feedback`),
  ADD KEY `id_inscricao` (`id_inscricao`);

--
-- Indexes for table `tb_feedback_comentario`
--
ALTER TABLE `tb_feedback_comentario`
  ADD PRIMARY KEY (`id_comentario`),
  ADD KEY `id_feedback` (`id_feedback`),
  ADD KEY `id_docente` (`id_docente`);

--
-- Indexes for table `tb_hae`
--
ALTER TABLE `tb_hae`
  ADD PRIMARY KEY (`id_hae`);

--
-- Indexes for table `tb_inscricao`
--
ALTER TABLE `tb_inscricao`
  ADD PRIMARY KEY (`id_inscricao`),
  ADD KEY `id_docente` (`id_docente`),
  ADD KEY `id_hae` (`id_hae`),
  ADD KEY `id_projeto` (`id_projeto`);

--
-- Indexes for table `tb_projeto`
--
ALTER TABLE `tb_projeto`
  ADD PRIMARY KEY (`id_projeto`),
  ADD KEY `id_hae` (`id_hae`);

--
-- Indexes for table `tb_relatorio`
--
ALTER TABLE `tb_relatorio`
  ADD PRIMARY KEY (`id_relatorio`),
  ADD KEY `id_projeto` (`id_projeto`),
  ADD KEY `id_feedback` (`id_feedback`);

--
-- Indexes for table `tb_relatorio_feedback`
--
ALTER TABLE `tb_relatorio_feedback`
  ADD PRIMARY KEY (`id_relatorio_feedback`),
  ADD KEY `id_relatorio` (`id_relatorio`),
  ADD KEY `id_feedback` (`id_feedback`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_chamada`
--
ALTER TABLE `tb_chamada`
  MODIFY `id_chamada` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_docente`
--
ALTER TABLE `tb_docente`
  MODIFY `id_docente` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_feedback`
--
ALTER TABLE `tb_feedback`
  MODIFY `id_feedback` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_feedback_comentario`
--
ALTER TABLE `tb_feedback_comentario`
  MODIFY `id_comentario` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_hae`
--
ALTER TABLE `tb_hae`
  MODIFY `id_hae` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_inscricao`
--
ALTER TABLE `tb_inscricao`
  MODIFY `id_inscricao` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_projeto`
--
ALTER TABLE `tb_projeto`
  MODIFY `id_projeto` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_relatorio`
--
ALTER TABLE `tb_relatorio`
  MODIFY `id_relatorio` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_relatorio_feedback`
--
ALTER TABLE `tb_relatorio_feedback`
  MODIFY `id_relatorio_feedback` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_chamada`
--
ALTER TABLE `tb_chamada`
  ADD CONSTRAINT `tb_chamada_ibfk_1` FOREIGN KEY (`id_hae`) REFERENCES `tb_hae` (`id_hae`),
  ADD CONSTRAINT `tb_chamada_ibfk_2` FOREIGN KEY (`id_inscricao`) REFERENCES `tb_inscricao` (`id_inscricao`);

--
-- Constraints for table `tb_feedback`
--
ALTER TABLE `tb_feedback`
  ADD CONSTRAINT `tb_feedback_ibfk_1` FOREIGN KEY (`id_inscricao`) REFERENCES `tb_inscricao` (`id_inscricao`);

--
-- Constraints for table `tb_feedback_comentario`
--
ALTER TABLE `tb_feedback_comentario`
  ADD CONSTRAINT `tb_feedback_comentario_ibfk_1` FOREIGN KEY (`id_feedback`) REFERENCES `tb_feedback` (`id_feedback`),
  ADD CONSTRAINT `tb_feedback_comentario_ibfk_2` FOREIGN KEY (`id_docente`) REFERENCES `tb_docente` (`id_docente`);

--
-- Constraints for table `tb_inscricao`
--
ALTER TABLE `tb_inscricao`
  ADD CONSTRAINT `tb_inscricao_ibfk_1` FOREIGN KEY (`id_docente`) REFERENCES `tb_docente` (`id_docente`),
  ADD CONSTRAINT `tb_inscricao_ibfk_2` FOREIGN KEY (`id_hae`) REFERENCES `tb_hae` (`id_hae`),
  ADD CONSTRAINT `tb_inscricao_ibfk_3` FOREIGN KEY (`id_projeto`) REFERENCES `tb_projeto` (`id_projeto`);

--
-- Constraints for table `tb_projeto`
--
ALTER TABLE `tb_projeto`
  ADD CONSTRAINT `tb_projeto_ibfk_1` FOREIGN KEY (`id_hae`) REFERENCES `tb_hae` (`id_hae`);

--
-- Constraints for table `tb_relatorio`
--
ALTER TABLE `tb_relatorio`
  ADD CONSTRAINT `tb_relatorio_ibfk_1` FOREIGN KEY (`id_projeto`) REFERENCES `tb_projeto` (`id_projeto`),
  ADD CONSTRAINT `tb_relatorio_ibfk_2` FOREIGN KEY (`id_feedback`) REFERENCES `tb_feedback` (`id_feedback`);

--
-- Constraints for table `tb_relatorio_feedback`
--
ALTER TABLE `tb_relatorio_feedback`
  ADD CONSTRAINT `tb_relatorio_feedback_ibfk_1` FOREIGN KEY (`id_relatorio`) REFERENCES `tb_relatorio` (`id_relatorio`),
  ADD CONSTRAINT `tb_relatorio_feedback_ibfk_2` FOREIGN KEY (`id_feedback`) REFERENCES `tb_feedback` (`id_feedback`);

--
-- Clear existing data (in correct order due to foreign key constraints)
--
DELETE FROM tb_relatorio_feedback;
DELETE FROM tb_relatorio;
DELETE FROM tb_feedback_comentario;
DELETE FROM tb_feedback;
DELETE FROM tb_chamada;
DELETE FROM tb_inscricao;
DELETE FROM tb_projeto;
DELETE FROM tb_hae;
DELETE FROM tb_docente;

--
-- Reset AUTO_INCREMENT counters
--
ALTER TABLE tb_docente AUTO_INCREMENT = 1;
ALTER TABLE tb_hae AUTO_INCREMENT = 1;
ALTER TABLE tb_projeto AUTO_INCREMENT = 1;
ALTER TABLE tb_inscricao AUTO_INCREMENT = 1;
ALTER TABLE tb_feedback AUTO_INCREMENT = 1;
ALTER TABLE tb_feedback_comentario AUTO_INCREMENT = 1;
ALTER TABLE tb_chamada AUTO_INCREMENT = 1;
ALTER TABLE tb_relatorio AUTO_INCREMENT = 1;
ALTER TABLE tb_relatorio_feedback AUTO_INCREMENT = 1;

--
-- Insert comprehensive faculty data
--
INSERT INTO tb_docente (nome, RG, email, matricula, turno, senha, cargo, outras_fatecs, curso) VALUES
('Maria Silva Santos', '123456789', 'maria.santos@fatec.sp.gov.br', 1001, 'Matutino', 'senha123', 'Diretor', 0, 'DSM'),
('João Carlos Oliveira', '234567890', 'joao.oliveira@fatec.sp.gov.br', 1002, 'Noturno', 'senha456', 'Coordenador', 1, 'DSM'),
('Ana Paula Costa', '345678901', 'ana.costa@fatec.sp.gov.br', 1003, 'Vespertino', 'senha789', 'Professor', 0, 'GPI'),
('Carlos Eduardo Lima', '456789012', 'carlos.lima@fatec.sp.gov.br', 1004, 'Noturno', 'senha101', 'Professor', 2, 'DSM'),
('Fernanda Almeida', '567890123', 'fernanda.almeida@fatec.sp.gov.br', 1005, 'Matutino', 'senha202', 'Secretaria', 0, 'ADM'),
('Roberto Mendes', '678901234', 'roberto.mendes@fatec.sp.gov.br', 1006, 'Noturno', 'senha303', 'Professor', 1, 'GPI'),
('Juliana Ferreira', '789012345', 'juliana.ferreira@fatec.sp.gov.br', 1007, 'Vespertino', 'senha404', 'Coordenador', 0, 'GPI');

--
-- Insert diverse HAE activities
--
INSERT INTO tb_hae (titulo, tip_hae, quant_hae, descricao, data_inicio, data_final) VALUES
('Monitoria de Algoritmos e Programação', 'DSM', 40, 'Atividade de monitoria para auxiliar alunos com dificuldades em algoritmos e lógica de programação, incluindo atendimento individual e em grupos.', '2025-02-01', '2025-06-30'),
('Orientação de TCC - Desenvolvimento de Sistemas', 'DSM', 60, 'Orientação de trabalhos de conclusão de curso na área de desenvolvimento de sistemas móveis e web, incluindo definição de escopo, metodologia e acompanhamento.', '2025-03-01', '2025-11-30'),
('Projeto de Extensão - Inclusão Digital', 'GPI', 80, 'Projeto voltado para capacitação em informática básica para a terceira idade e pessoas em situação de vulnerabilidade social na região de Itapira.', '2025-02-15', '2025-12-15'),
('Monitoria de Gestão de Projetos', 'GPI', 30, 'Apoio aos estudantes na compreensão de metodologias ágeis, ferramentas de gestão e elaboração de cronogramas de projetos.', '2025-03-01', '2025-07-31'),
('Pesquisa em Inteligência Artificial', 'DSM', 100, 'Desenvolvimento de algoritmos de machine learning aplicados a problemas do cotidiano, com foco em visão computacional e processamento de linguagem natural.', '2025-01-15', '2025-12-31'),
('Organização de Eventos Acadêmicos', 'ADM', 25, 'Apoio na organização da Semana de Tecnologia da FATEC, incluindo planejamento, contato com palestrantes e coordenação logística.', '2025-04-01', '2025-05-31'),
('Desenvolvimento de Sistema Interno', 'DSM', 120, 'Criação e manutenção de sistema web para controle acadêmico interno da instituição, utilizando tecnologias modernas de desenvolvimento.', '2025-02-01', '2025-12-31');

--
-- Insert detailed project data
--
INSERT INTO tb_projeto (data_inicio, data_final, titulo, id_hae, descricoes, dias_exec) VALUES
('2025-02-01', '2025-06-30', 'Monitoria Algoritmos - Turma A', 1, 
'{"objetivos": "Melhorar o desempenho acadêmico dos alunos em algoritmos através de aulas de reforço e exercícios práticos", "justificativa": "Alto índice de reprovação na disciplina e necessidade de suporte adicional aos estudantes", "metodologia": "Aulas expositivas, resolução de exercícios em grupo, atendimento individualizado e desenvolvimento de projetos práticos", "cronograma": "Fevereiro: Diagnóstico inicial e planejamento\\nMarço-Maio: Aulas de reforço semanais\\nJunho: Avaliação final e relatório", "metas": "Reduzir em 30% o índice de reprovação, atender pelo menos 50 alunos, criar banco de exercícios", "recursos": "Laboratório de informática, projetor, quadro, material didático, acesso à internet", "resultado_esperado": "Melhoria significativa no rendimento acadêmico e maior confiança dos alunos na disciplina"}', 
'["SEG,N,19-21", "QUA,N,19-21"]'),

('2025-03-01', '2025-11-30', 'Sistema de Gestão Acadêmica', 2, 
'{"objetivos": "Desenvolver sistema web completo para gestão de notas, frequência e comunicação entre professores e alunos", "justificativa": "Necessidade de modernização dos processos acadêmicos e centralização das informações", "metodologia": "Desenvolvimento ágil com Scrum, utilizando React.js no frontend e Node.js no backend, banco de dados MySQL", "cronograma": "Março-Abril: Levantamento de requisitos\\nMaio-Agosto: Desenvolvimento\\nSetembro-Outubro: Testes\\nNovembro: Implantação", "metas": "Sistema 100% funcional, interface intuitiva, alta disponibilidade, suporte a 500+ usuários simultâneos", "recursos": "Servidor web, banco de dados, ferramentas de desenvolvimento, equipe de 3 desenvolvedores", "resultado_esperado": "Otimização dos processos acadêmicos, redução de 50% no tempo de consultas e maior satisfação dos usuários"}', 
'["TER,M,08-12", "QUI,M,08-12", "SEX,M,08-12"]'),

('2025-02-15', '2025-12-15', 'Inclusão Digital Terceira Idade', 3, 
'{"objetivos": "Capacitar 200 pessoas da terceira idade em informática básica e uso de tecnologias digitais", "justificativa": "Crescente necessidade de inclusão digital da população idosa, especialmente após a pandemia", "metodologia": "Cursos presenciais de 20h, material didático adaptado, ensino personalizado com apoio de monitores", "cronograma": "Fevereiro-Março: Preparação e divulgação\\nAbril-Novembro: Execução dos cursos\\nDezembro: Avaliação e relatório final", "metas": "8 turmas de 25 alunos, 90% de aproveitamento, criação de material didático específico", "recursos": "Laboratório com 25 computadores, impressora, material didático, coffee break", "resultado_esperado": "Maior autonomia digital dos idosos, redução da exclusão digital e fortalecimento dos vínculos com a comunidade"}', 
'["SAB,M,08-12"]'),

('2025-03-01', '2025-07-31', 'Consultoria em Gestão de Projetos', 4, 
'{"objetivos": "Prestar consultoria gratuita em gestão de projetos para micro e pequenas empresas da região", "justificativa": "Necessidade de apoio técnico especializado para MPEs locais melhorarem sua competitividade", "metodologia": "Diagnóstico empresarial, elaboração de planos de projeto, implementação de metodologias ágeis, acompanhamento", "cronograma": "Março: Seleção das empresas\\nAbril-Junho: Consultoria ativa\\nJulho: Avaliação de resultados", "metas": "Atender 15 empresas, implementar metodologias em 80% delas, gerar 20% de melhoria na eficiência", "recursos": "Transporte, material de escritório, software de gestão, equipe de consultores", "resultado_esperado": "Fortalecimento do empreendedorismo local, melhoria na gestão empresarial e criação de parcerias duradouras"}', 
'["QUA,V,14-18", "SEX,V,14-18"]'),

('2025-01-15', '2025-12-31', 'IA para Diagnóstico Médico', 5, 
'{"objetivos": "Desenvolver algoritmo de IA para auxiliar no diagnóstico precoce de doenças através de análise de imagens médicas", "justificativa": "Potencial de impacto social significativo na área da saúde e necessidade de pesquisa aplicada", "metodologia": "Revisão bibliográfica, coleta de dataset, treinamento de redes neurais, validação com especialistas", "cronograma": "Janeiro-Março: Revisão e planejamento\\nAbril-Setembro: Desenvolvimento\\nOutubro-Dezembro: Testes e validação", "metas": "Precisão de 85% no diagnóstico, validação com 3 hospitais, publicação de artigo científico", "recursos": "Servidor GPU, acesso a bases de dados médicos, software especializado, parceria com hospitais", "resultado_esperado": "Ferramenta funcional para auxílio médico, contribuição científica relevante e possível patente"}', 
'["SEG,I,08-17", "TER,I,08-17", "QUA,I,08-17"]');

--
-- Insert registration data
--
INSERT INTO tb_inscricao (id_docente, id_hae, data_envio, quant_hae, outras_fatecs, id_projeto, status) VALUES
(3, 1, '2025-01-20 10:30:00', 40, 0, 1, 'Aprovada'),
(4, 2, '2025-01-25 14:15:00', 60, 1, 2, 'Aprovada'),
(6, 3, '2025-02-01 09:45:00', 80, 0, 3, 'Em análise'),
(3, 4, '2025-02-10 16:20:00', 30, 0, 4, 'Aprovada'),
(4, 5, '2025-01-10 11:00:00', 100, 2, 5, 'Pendente'),
(7, 1, '2025-02-15 13:30:00', 25, 0, NULL, 'Reprovada'),
(6, 4, '2025-02-20 08:45:00', 35, 1, NULL, 'Pendente');

--
-- Insert feedback data
--
INSERT INTO tb_feedback (id_inscricao, data_envio, resultado) VALUES
(1, '2025-01-22 15:30:00', 'Aprovada'),
(2, '2025-01-28 10:15:00', 'Aprovada'),
(4, '2025-02-12 14:45:00', 'Aprovada'),
(6, '2025-02-17 16:20:00', 'Reprovada');

--
-- Insert feedback comments
--
INSERT INTO tb_feedback_comentario (id_feedback, cargo, id_docente, comentario) VALUES
(1, 'Coordenador', 2, 'Projeto muito bem estruturado. A metodologia proposta é adequada e os objetivos são claros e alcançáveis. Aprovado para execução imediata.'),
(2, 'Diretor', 1, 'Excelente proposta de sistema. A instituição realmente necessita desta modernização. Recurso aprovado integralmente. Parabenizo pela iniciativa.'),
(3, 'Coordenador', 7, 'Projeto de grande relevância social. A metodologia está bem definida e os recursos solicitados são apropriados. Aprovado com expectativa de excelentes resultados.'),
(4, 'Diretor', 1, 'Infelizmente, a proposta apresentada não atende aos critérios estabelecidos. A justificativa é insuficiente e faltam detalhes sobre a metodologia. Recomendo reformulação e nova submissão.');

--
-- Insert call data
--
INSERT INTO tb_chamada (id_hae, id_inscricao, data_envio, quant_hae, status, justificativa, num_chamada, semestre) VALUES
(1, 1, '2025-02-01 12:00:00', 40, 'Deferido', 'Projeto aprovado após análise técnica. Professor demonstrou competência e disponibilidade adequada.', 1, '1'),
(2, 2, '2025-02-05 14:30:00', 60, 'Deferido', 'Desenvolvimento de sistema estratégico para a instituição. Projeto prioritário com recursos garantidos.', 1, '1'),
(4, 4, '2025-02-15 16:45:00', 30, 'Deferido', 'Projeto de extensão com grande impacto social. Metodologia bem definida e recursos adequados.', 2, '1');

--
-- Insert report data
--
INSERT INTO tb_relatorio (id_projeto, data_entrega, pdf_url, id_feedback, descricoes, pdf_nome, pdf_original_nome) VALUES
(1, '2025-04-15 17:30:00', '/uploads/relatorios/1/relatorio_monitoria_algoritmos_abr2025.pdf', NULL, 
'{"objetivos": "Melhorar o desempenho acadêmico dos alunos em algoritmos através de aulas de reforço e exercícios práticos", "resultados": "Foram atendidos 62 alunos em 8 semanas de monitoria. Houve redução de 35% no índice de reprovação da disciplina.", "metodologia": "Aulas expositivas, resolução de exercícios em grupo, atendimento individualizado e desenvolvimento de projetos práticos", "cronograma": "Fevereiro: Diagnóstico inicial e planejamento - CONCLUÍDO\\nMarço-Abril: Aulas de reforço semanais - CONCLUÍDO\\nMaio-Junho: Continuidade e avaliação - EM ANDAMENTO", "metas": "Meta de redução de 30% na reprovação foi superada (35%). Atendimento de 50 alunos foi superado (62 alunos). Banco de exercícios criado com 150 questões.", "recursos": "Laboratório de informática, projetor, quadro, material didático, acesso à internet - todos utilizados conforme planejado", "aproveitamento": "Excelente aproveitamento. 89% dos alunos atendidos tiveram melhoria nas notas. Feedback muito positivo.", "anotacoes": "Projeto superou expectativas. Recomenda-se continuidade no próximo semestre com expansão para outras disciplinas."}', 
'relatorio_monitoria_algoritmos_abr2025.pdf', 'Relatório Monitoria Algoritmos - Abril 2025'),

(2, '2025-06-30 23:59:00', '/uploads/relatorios/2/relatorio_sistema_gestao_jun2025.pdf', NULL, 
'{"objetivos": "Desenvolver sistema web completo para gestão de notas, frequência e comunicação entre professores e alunos", "resultados": "Sistema 80% concluído. Módulos de gestão de notas e frequência funcionais. Módulo de comunicação em fase final de testes.", "metodologia": "Desenvolvimento ágil com Scrum, utilizando React.js no frontend e Node.js no backend, banco de dados MySQL", "cronograma": "Março-Abril: Levantamento de requisitos - CONCLUÍDO\\nMaio-Junho: Desenvolvimento - 80% CONCLUÍDO\\nJulho-Agosto: Testes e correções - INICIADO", "metas": "Sistema funcional em desenvolvimento. Interface intuitiva implementada. Testes de carga para 200 usuários simultâneos realizados com sucesso.", "recursos": "Servidor web, banco de dados, ferramentas de desenvolvimento, equipe de 3 desenvolvedores - recursos utilizados adequadamente", "aproveitamento": "Muito bom aproveitamento. Cronograma ligeiramente atrasado devido à complexidade maior que o previsto.", "anotacoes": "Necessário extensão de prazo de 2 meses para conclusão completa e testes finais. Qualidade do sistema está excelente."}', 
'relatorio_sistema_gestao_jun2025.pdf', 'Relatório Sistema Gestão - Junho 2025');

--
-- Insert report feedback relationship
--
INSERT INTO tb_relatorio_feedback (id_relatorio, id_feedback) VALUES
(1, 1),
(2, 2);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;