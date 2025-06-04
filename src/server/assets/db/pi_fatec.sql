-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Jun 03, 2025 at 12:19 AM
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
-- Table structure for table `tb_banco_de_horas`
--

CREATE TABLE `tb_banco_de_horas` (
  `id_bdhrs` int NOT NULL,
  `dias` varchar(4) DEFAULT NULL,
  `turno` varchar(8) DEFAULT NULL,
  `horas` varchar(6) DEFAULT NULL,
  `id_inscricao` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_chamada`
--

CREATE TABLE `tb_chamada` (
  `id_chamada` int NOT NULL,
  `id_hae` int DEFAULT NULL,
  `id_inscricao` int DEFAULT NULL,
  `data_envio` datetime NOT NULL
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

--
-- Dumping data for table `tb_docente`
--

INSERT INTO `tb_docente` (`id_docente`, `nome`, `RG`, `email`, `matricula`, `turno`, `senha`, `cargo`, `outras_fatecs`, `curso`) VALUES
(1, 'Danilo Alves dos Santos', '50388884886', 'daniloasan.itapira@gmail.com', 123456789, 'Noturno', '1234', 'Diretor', 0, 'DSM'),
(2, 'Jose Junior Pinto', '12345678912', 'junior.professor@gmail.com', 123456879, 'Noturno', '12345', 'Professor', 1, 'DSM');

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

--
-- Dumping data for table `tb_feedback`
--

INSERT INTO `tb_feedback` (`id_feedback`, `id_inscricao`, `data_envio`, `resultado`) VALUES
(1, 13, '2025-05-26 16:19:40', 'Reprovada'),
(2, 13, '2025-05-26 16:40:31', 'Reprovada'),
(3, 14, '2025-05-27 14:41:57', 'Aprovada');

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

--
-- Dumping data for table `tb_feedback_comentario`
--

INSERT INTO `tb_feedback_comentario` (`id_comentario`, `id_feedback`, `cargo`, `id_docente`, `comentario`) VALUES
(1, 1, 'Diretor', 1, 'Infelizmente, sua solicitação foi reprovada.\nApós análise criteriosa, identificamos que as informações ou a carga horária apresentada não atendem aos critérios estabelecidos.\n\nCaso tenha dúvidas ou deseje ajustar sua solicitação, estamos à disposição para ajudar. Não desanime — novas oportunidades virão!'),
(2, 2, 'Diretor', 1, 'Muito ruim'),
(3, 3, 'Diretor', 1, 'Aprovado com exito');

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

--
-- Dumping data for table `tb_hae`
--

INSERT INTO `tb_hae` (`id_hae`, `titulo`, `tip_hae`, `quant_hae`, `descricao`, `data_inicio`, `data_final`) VALUES
(1, 'Munitoramento de Estágio', 'DSM', 6, 'dasdsadsa 6s5a1d65s 165sa1d6 sa16ds161a6s 51d6s6 6d1sa51 6as5d a6s1', '2025-05-22', '2025-05-31'),
(2, 'Munitoramento de TCC', 'GPI', 6, 'Monitorar adsas sd ada sadasdasda asasd as sa das das das das das dsa d ', '2025-08-05', '2025-11-30');

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

--
-- Dumping data for table `tb_inscricao`
--

INSERT INTO `tb_inscricao` (`id_inscricao`, `id_docente`, `id_hae`, `data_envio`, `quant_hae`, `outras_fatecs`, `id_projeto`, `status`) VALUES
(13, 2, 1, '2025-05-21 17:32:14', 2, 1, 15, 'Reprovada'),
(14, 2, 2, '2025-05-27 16:59:22', 5, 0, 16, 'Aprovada'),
(15, 2, 1, '2025-06-02 20:58:49', 2, 1, 17, 'Pendente');

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

--
-- Dumping data for table `tb_projeto`
--

INSERT INTO `tb_projeto` (`id_projeto`, `data_inicio`, `data_final`, `titulo`, `id_hae`, `descricoes`, `dias_exec`) VALUES
(15, '2025-08-05', '2025-12-08', 'Projeto meu tcc', 1, '{\"metas\": \"metas metas\", \"recursos\": \"Sala, Mesa, Ar Condicionado\", \"objetivos\": \"objetivos e objetivos\", \"cronograma\": \"Agosto:....\\r\\nsetembro: amarelo\", \"metodologia\": \"metodologias a serem\", \"justificativa\": \"justificando\", \"resultado_esperado\": \"esperando ....\"}', '[\"SEX,N,18-22\"]'),
(16, '2025-06-27', '2025-12-31', 'Proejto TCC MAX', 2, '{\"metas\": \"Metas e metas. sdasdk jlsasdhaskljdah s\", \"recursos\": \"Sala, Mesa, Ar Condicionado, computador bom\", \"objetivos\": \"dsaldkj sahdakjs hsadlkjas h\", \"cronograma\": \"lkjsdhasd lsakdjahs asldk jsadh aslkdjash daskjdh \", \"metodologia\": \"kljshdaskl asjdhas dklsadjh as\", \"justificativa\": \"lkjalkdjhasdasldk jash aslkdjash\", \"resultado_esperado\": \"asdas kdaskj ash daskjh\"}', '[\"TER,N,18-22\"]'),
(17, '2025-06-28', '2025-07-12', 'Projeto Senac', 1, '{\"metas\": \"dasd sad6 5as4\", \"recursos\": \"d65as6d as4da65s4\", \"objetivos\": \"5sa6d54as65 d4as65d4\", \"cronograma\": \"6d54as6d4as65d4a \", \"metodologia\": \"654sa65d4as6d 4as64\", \"justificativa\": \"654sa6d54as6d as465\", \"resultado_esperado\": \"sd64as6d5sa4d6asd54\"}', '[\"SEG,N,18-22\"]');

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

--
-- Dumping data for table `tb_relatorio`
--

INSERT INTO `tb_relatorio` (`id_relatorio`, `id_projeto`, `data_entrega`, `pdf_url`, `id_feedback`, `descricoes`, `pdf_nome`, `pdf_original_nome`) VALUES
(14, 16, '2025-06-02 11:37:03', '/Applications/MAMP/htdocs/Fatec-pi-1/src/server/assets/uploads/relatorios/2/relatorio_683db70f6a0a74.47331155_1748875023.pdf', NULL, '{\"metas\": \"Metas e metas. sdasdk jlsasdhaskljdah s\", \"recursos\": \"Sala, Mesa, Ar Condicionado, computador bom\", \"anotacoes\": \"das dkaskdjashjk hdasjkl\", \"objetivos\": \"dsaldkj sahdakjs hsadlkjas h\", \"cronograma\": \"lkjsdhasd lsakdjahs asldk jsadh aslkdjash daskjdh \", \"resultados\": \"dsadjs kjdh sakjdh aksjh\", \"metodologia\": \"kljshdaskl asjdhas dklsadjh as\", \"justificativa\": \"lkjalkdjhasdasldk jash aslkdjash\", \"aproveitamento\": \"dsa dasd sadksajhkjsa dh\", \"resultado_esperado\": \"asdas kdaskj ash daskjh\"}', 'relatorio_683db70f6a0a74.47331155_1748875023.pdf', 'Danilo-resume-EN');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_banco_de_horas`
--
ALTER TABLE `tb_banco_de_horas`
  ADD PRIMARY KEY (`id_bdhrs`),
  ADD KEY `id_inscricao` (`id_inscricao`);

--
-- Indexes for table `tb_chamada`
--
ALTER TABLE `tb_chamada`
  ADD PRIMARY KEY (`id_chamada`),
  ADD KEY `id_hae` (`id_hae`),
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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_banco_de_horas`
--
ALTER TABLE `tb_banco_de_horas`
  MODIFY `id_bdhrs` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_chamada`
--
ALTER TABLE `tb_chamada`
  MODIFY `id_chamada` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_docente`
--
ALTER TABLE `tb_docente`
  MODIFY `id_docente` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tb_feedback`
--
ALTER TABLE `tb_feedback`
  MODIFY `id_feedback` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tb_feedback_comentario`
--
ALTER TABLE `tb_feedback_comentario`
  MODIFY `id_comentario` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tb_hae`
--
ALTER TABLE `tb_hae`
  MODIFY `id_hae` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tb_inscricao`
--
ALTER TABLE `tb_inscricao`
  MODIFY `id_inscricao` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tb_projeto`
--
ALTER TABLE `tb_projeto`
  MODIFY `id_projeto` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tb_relatorio`
--
ALTER TABLE `tb_relatorio`
  MODIFY `id_relatorio` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_banco_de_horas`
--
ALTER TABLE `tb_banco_de_horas`
  ADD CONSTRAINT `tb_banco_de_horas_ibfk_1` FOREIGN KEY (`id_inscricao`) REFERENCES `tb_inscricao` (`id_inscricao`);

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
