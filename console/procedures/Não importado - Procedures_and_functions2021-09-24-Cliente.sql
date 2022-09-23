/*
SQLyog Professional v12.12 (64 bit)
MySQL - 10.4.13-MariaDB : Database - mgfolha_igaci
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `cad_bancos` */

DROP TABLE IF EXISTS `cad_bancos`;

CREATE TABLE `cad_bancos` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID do registro',
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(3) NOT NULL DEFAULT 10,
  `dominio` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Domínio do cliente',
  `evento` int(11) NOT NULL DEFAULT 0 COMMENT 'Evento do registro',
  `created_at` int(11) NOT NULL COMMENT 'Registro em',
  `updated_at` int(11) NOT NULL COMMENT 'Atualização em',
  `febraban` varchar(5) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Código febraban',
  `nome` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Nome do banco',
  `url_logo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Url da logo do banco',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  UNIQUE KEY `cad_bancos_dominio_febraban` (`dominio`,`febraban`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `cad_bconvenios` */

DROP TABLE IF EXISTS `cad_bconvenios`;

CREATE TABLE `cad_bconvenios` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID do registro',
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(3) NOT NULL DEFAULT 10,
  `dominio` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Domínio do cliente',
  `evento` int(11) NOT NULL DEFAULT 0 COMMENT 'Evento do registro',
  `created_at` int(11) NOT NULL COMMENT 'Registro em',
  `updated_at` int(11) NOT NULL COMMENT 'Atualização em',
  `id_cad_bancos` int(11) NOT NULL COMMENT 'Banco conveniado',
  `id_orgao_ua` int(11) NOT NULL COMMENT 'Unidade autônoma',
  `convenio` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Convênio',
  `cod_compromisso` varchar(4) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Código do compromisso',
  `agencia` varchar(15) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Agência',
  `a_digito` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Ag Dígito',
  `conta` varchar(15) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Conta',
  `c_digito` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'C Dígito',
  `convenio_nr` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Convênio',
  `tipo_servico` int(11) DEFAULT NULL COMMENT 'Tipo serviço',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  UNIQUE KEY `cad_bconvenios_dominio-convenio-id_cad_bancos-id_orgao_ua` (`dominio`,`convenio_nr`,`convenio`,`id_cad_bancos`,`id_orgao_ua`,`agencia`,`c_digito`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `cad_cargos` */

DROP TABLE IF EXISTS `cad_cargos`;

CREATE TABLE `cad_cargos` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID do registro',
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(3) NOT NULL DEFAULT 10,
  `dominio` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Domínio do cliente',
  `evento` int(11) NOT NULL DEFAULT 0 COMMENT 'Evento do registro',
  `created_at` int(11) NOT NULL COMMENT 'Registro em',
  `updated_at` int(11) NOT NULL COMMENT 'Atualização em',
  `id_cargo` varchar(4) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Código',
  `nome` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Nome',
  `cbo` varchar(7) COLLATE utf8_unicode_ci NOT NULL COMMENT 'C.B.O.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  UNIQUE KEY `cad_cargos-dominio-id_cargo` (`dominio`,`id_cargo`)
) ENGINE=InnoDB AUTO_INCREMENT=297 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `cad_centros` */

DROP TABLE IF EXISTS `cad_centros`;

CREATE TABLE `cad_centros` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID do registro',
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(3) NOT NULL DEFAULT 10,
  `dominio` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Domínio do cliente',
  `evento` int(11) NOT NULL DEFAULT 0 COMMENT 'Evento do registro',
  `created_at` int(11) NOT NULL COMMENT 'Registro em',
  `updated_at` int(11) NOT NULL COMMENT 'Atualização em',
  `cod_centro` varchar(4) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Código centro',
  `nome_centro` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Nome centro de custo',
  `cnpj_ua` varchar(14) COLLATE utf8_unicode_ci NOT NULL COMMENT 'CNPJ U.A.',
  `cod_ua` varchar(4) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Código U.A.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  UNIQUE KEY `cad_centros_dominio_cod-centro` (`dominio`,`cod_centro`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `cad_classes` */

DROP TABLE IF EXISTS `cad_classes`;

CREATE TABLE `cad_classes` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID do registro',
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(3) NOT NULL DEFAULT 10,
  `dominio` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Domínio do cliente',
  `evento` int(11) NOT NULL DEFAULT 0 COMMENT 'Evento do registro',
  `created_at` int(11) NOT NULL COMMENT 'Registro em',
  `updated_at` int(11) NOT NULL COMMENT 'Atualização em',
  `id_pccs` int(11) NOT NULL COMMENT 'ID PCCS',
  `id_classe` int(11) NOT NULL COMMENT 'Classe',
  `nome_classe` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Nome',
  `i_ano_inicial` int(3) NOT NULL COMMENT 'Ano inicial',
  `i_ano_final` int(3) NOT NULL COMMENT 'Ano final',
  PRIMARY KEY (`id`,`id_pccs`,`id_classe`),
  UNIQUE KEY `slug` (`slug`),
  UNIQUE KEY `cad_classes-dominio-id_pccs-id_classe` (`dominio`,`id_pccs`,`id_classe`)
) ENGINE=InnoDB AUTO_INCREMENT=1066 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `cad_departamentos` */

DROP TABLE IF EXISTS `cad_departamentos`;

CREATE TABLE `cad_departamentos` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID do registro',
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(3) NOT NULL DEFAULT 10,
  `dominio` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Domínio do cliente',
  `evento` int(11) NOT NULL DEFAULT 0 COMMENT 'Evento do registro',
  `created_at` int(11) NOT NULL COMMENT 'Registro em',
  `updated_at` int(11) NOT NULL COMMENT 'Atualização em',
  `id_departamento` int(4) NOT NULL COMMENT 'Locação',
  `departamento` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Nome',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  UNIQUE KEY `cad_departamentos_dominio-id_departamento` (`dominio`,`id_departamento`)
) ENGINE=InnoDB AUTO_INCREMENT=108 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `cad_funcoes` */

DROP TABLE IF EXISTS `cad_funcoes`;

CREATE TABLE `cad_funcoes` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID do registro',
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(3) NOT NULL DEFAULT 10,
  `dominio` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Domínio do cliente',
  `evento` int(11) NOT NULL DEFAULT 0 COMMENT 'Evento do registro',
  `created_at` int(11) NOT NULL COMMENT 'Registro em',
  `updated_at` int(11) NOT NULL COMMENT 'Atualização em',
  `id_funcao` int(4) NOT NULL COMMENT 'Funcao',
  `funcao` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Nome',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  UNIQUE KEY `cad_funcoes_dominio-id_funcao` (`dominio`,`id_funcao`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `cad_localtrabalho` */

DROP TABLE IF EXISTS `cad_localtrabalho`;

CREATE TABLE `cad_localtrabalho` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID do registro',
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(3) NOT NULL DEFAULT 10,
  `dominio` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Domínio do cliente',
  `evento` int(11) NOT NULL DEFAULT 0 COMMENT 'Evento do registro',
  `created_at` int(11) NOT NULL COMMENT 'Registro em',
  `updated_at` int(11) NOT NULL COMMENT 'Atualização em',
  `id_local_trabalho` int(4) NOT NULL COMMENT 'Locação',
  `nome` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Nome',
  `siope` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Código SIOPE',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  UNIQUE KEY `cad_localtrabalho_dominio-id_local_trabalho-siope` (`dominio`,`id_local_trabalho`,`siope`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `cad_pccs` */

DROP TABLE IF EXISTS `cad_pccs`;

CREATE TABLE `cad_pccs` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID do registro',
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(3) NOT NULL DEFAULT 10,
  `dominio` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Domínio do cliente',
  `evento` int(11) NOT NULL DEFAULT 0 COMMENT 'Evento do registro',
  `created_at` int(11) NOT NULL COMMENT 'Registro em',
  `updated_at` int(11) NOT NULL COMMENT 'Atualização em',
  `id_pccs` int(11) NOT NULL COMMENT 'ID PCCS',
  `nome_pccs` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Nome',
  `nivel_pccs` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Nível',
  PRIMARY KEY (`id`,`id_pccs`),
  UNIQUE KEY `slug` (`slug`),
  UNIQUE KEY `cad_pccs-dominio-id_pccs` (`dominio`,`id_pccs`)
) ENGINE=InnoDB AUTO_INCREMENT=257 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `cad_scertidao` */

DROP TABLE IF EXISTS `cad_scertidao`;

CREATE TABLE `cad_scertidao` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID do registro',
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(3) NOT NULL DEFAULT 10,
  `dominio` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Domínio do cliente',
  `evento` int(11) NOT NULL DEFAULT 0 COMMENT 'Evento do registro',
  `created_at` int(11) NOT NULL COMMENT 'Registro em',
  `updated_at` int(11) NOT NULL COMMENT 'Atualização em',
  `id_cad_servidores` int(11) NOT NULL COMMENT 'ID do servidor',
  `tipo` tinyint(3) DEFAULT NULL COMMENT 'Tipo',
  `certidao` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Certidão',
  `emissao` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Emissão',
  `cartorio` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Cartório',
  `uf` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Uf',
  `cidade` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Municipio',
  `termo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Termo',
  `livro` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Livro',
  `folha` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Folha',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  UNIQUE KEY `cad_scertidao_id_cad_servidores-tipo-emissao` (`id_cad_servidores`,`tipo`,`emissao`),
  CONSTRAINT `fk-cad_scertidao-id_cad_servidores-cad_servidores-id` FOREIGN KEY (`id_cad_servidores`) REFERENCES `cad_servidores` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=113 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `cad_sdependentes` */

DROP TABLE IF EXISTS `cad_sdependentes`;

CREATE TABLE `cad_sdependentes` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID do registro',
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(3) NOT NULL DEFAULT 10,
  `dominio` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Domínio do cliente',
  `evento` int(11) NOT NULL DEFAULT 0 COMMENT 'Evento do registro',
  `created_at` int(11) NOT NULL COMMENT 'Registro em',
  `updated_at` int(11) NOT NULL COMMENT 'Atualização em',
  `id_cad_servidores` int(11) NOT NULL COMMENT 'ID do servidor',
  `matricula` int(11) NOT NULL COMMENT 'Matricula dependente',
  `nome` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Nome',
  `tipo` tinyint(3) NOT NULL COMMENT 'Tipo',
  `permanente` tinyint(3) NOT NULL COMMENT 'Permanente',
  `nascimento_d` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Nascimento data',
  `inss_prz` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Prazo INSS',
  `irrf_prz` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Prazo IRRF',
  `rpps_prz` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Prazo RPPS',
  `rg` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'RG',
  `rg_emissor` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'RG emissor',
  `rg_uf` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'RG UF',
  `rg_d` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'RG emissão',
  `certidao` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Certidão',
  `livro` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Livro',
  `folha` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Folha',
  `certidao_d` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Emissão',
  `carteira_vacinacao` tinyint(3) DEFAULT NULL COMMENT 'Cartão de vacinação',
  `historico_escolar` tinyint(3) DEFAULT NULL COMMENT 'Histórico escolar',
  `plano_saude` tinyint(3) DEFAULT NULL COMMENT 'Plano de saúde',
  `cpf` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'CPF',
  `relacao_dependecia` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Relação de dependência',
  `pensionista` tinyint(3) DEFAULT NULL COMMENT 'Pensionista',
  `irpf` tinyint(3) DEFAULT NULL COMMENT 'Dependente IRRF',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  UNIQUE KEY `cad_sdependentes-id_cad_servidores-matricula-dominio` (`id_cad_servidores`,`matricula`,`dominio`),
  CONSTRAINT `fk-cad_sdependentes-id_cad_servidores-cad_servidores-id` FOREIGN KEY (`id_cad_servidores`) REFERENCES `cad_servidores` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1125 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `cad_servidores` */

DROP TABLE IF EXISTS `cad_servidores`;

CREATE TABLE `cad_servidores` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID do registro',
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(3) NOT NULL DEFAULT 10,
  `dominio` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Domínio do cliente',
  `evento` int(11) NOT NULL DEFAULT 0 COMMENT 'Evento do registro',
  `created_at` int(11) NOT NULL COMMENT 'Registro em',
  `updated_at` int(11) NOT NULL COMMENT 'Atualização em',
  `url_foto` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Foto do servidor',
  `matricula` int(11) NOT NULL COMMENT 'Matricula',
  `nome` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Nome',
  `cpf` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'CPF',
  `rg` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'RG',
  `rg_emissor` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'RG emissor',
  `rg_uf` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'RG UF',
  `rg_d` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'RG emissão',
  `pispasep` varchar(14) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'PIS',
  `pispasep_d` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'PIS emissão',
  `titulo` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Título',
  `titulosecao` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Tit sessão',
  `titulozona` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Tit zona',
  `ctps` varchar(7) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'CTPS',
  `ctps_serie` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'CTPS série',
  `ctps_uf` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'CTPS UF',
  `ctps_d` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'CTPS emissão',
  `nascimento_d` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Nascimento',
  `pai` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Pai',
  `mae` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Mãe',
  `cep` varchar(8) COLLATE utf8_unicode_ci NOT NULL DEFAULT '00000000' COMMENT 'Cep',
  `logradouro` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Logradouro',
  `numero` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT 'Número',
  `complemento` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Complemento',
  `bairro` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Bairro',
  `cidade` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Cidade',
  `uf` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'AL' COMMENT 'Estado',
  `naturalidade` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Naturalidade',
  `naturalidade_uf` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Nat UF',
  `telefone` varchar(14) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Telefone',
  `celular` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Celular',
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Email',
  `idbanco` int(11) DEFAULT NULL COMMENT 'Banco',
  `banco_agencia` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Agência',
  `banco_agencia_digito` varchar(1) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Ag dígito',
  `banco_conta` varchar(12) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Conta',
  `banco_conta_digito` varchar(1) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Cta dígito',
  `banco_operacao` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Cta Operação',
  `nacionalidade` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Nacionalidade',
  `sexo` tinyint(3) DEFAULT NULL COMMENT 'Sexo',
  `raca` tinyint(3) DEFAULT NULL COMMENT 'Raça',
  `estado_civil` tinyint(3) DEFAULT NULL COMMENT 'Estado civil',
  `tipodeficiencia` tinyint(3) DEFAULT NULL COMMENT 'Tipo deficiência',
  `d_admissao` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Admissão',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  UNIQUE KEY `cad_servidores_dominio_matricula` (`dominio`,`matricula`)
) ENGINE=InnoDB AUTO_INCREMENT=2357 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `cad_sferias` */

DROP TABLE IF EXISTS `cad_sferias`;

CREATE TABLE `cad_sferias` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID do registro',
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(3) NOT NULL DEFAULT 10,
  `dominio` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Domínio do cliente',
  `evento` int(11) NOT NULL DEFAULT 0 COMMENT 'Evento do registro',
  `created_at` int(11) NOT NULL COMMENT 'Registro em',
  `updated_at` int(11) NOT NULL COMMENT 'Atualização em',
  `id_cad_servidores` int(11) NOT NULL COMMENT 'ID do servidor',
  `d_ferias` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Data inicial ds férias',
  `periodo` tinyint(10) DEFAULT 30 COMMENT 'Período',
  `observacoes` text COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Observações',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  UNIQUE KEY `cad_sferias_id_cad_servidores-certidao_tipo-d_ferias-dominio` (`id_cad_servidores`,`d_ferias`,`dominio`),
  CONSTRAINT `fk-cad_sferias-id_cad_servidores-cad_servidores-id` FOREIGN KEY (`id_cad_servidores`) REFERENCES `cad_servidores` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=945 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `cad_sfuncional` */

DROP TABLE IF EXISTS `cad_sfuncional`;

CREATE TABLE `cad_sfuncional` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID do registro',
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(3) NOT NULL DEFAULT 10,
  `dominio` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Domínio do cliente',
  `evento` int(11) NOT NULL DEFAULT 0 COMMENT 'Evento do registro',
  `created_at` int(11) NOT NULL COMMENT 'Registro em',
  `updated_at` int(11) NOT NULL COMMENT 'Atualização em',
  `ano` varchar(4) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Ano',
  `mes` varchar(2) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Mês',
  `parcela` varchar(3) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Parcela',
  `id_cad_servidores` int(11) NOT NULL COMMENT 'ID do servidor',
  `id_local_trabalho` int(11) DEFAULT NULL COMMENT 'Local de trabalho',
  `id_cad_principal` int(11) DEFAULT NULL COMMENT 'Vinculo principal',
  `id_escolaridade` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Escolaridade',
  `escolaridaderais` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Escolaridade RAIS',
  `rais` tinyint(3) DEFAULT NULL COMMENT 'Declara RAIS',
  `dirf` tinyint(3) DEFAULT NULL COMMENT 'Declara DIRF',
  `sefip` tinyint(3) DEFAULT NULL COMMENT 'Declara SEFIP',
  `sicap` tinyint(3) DEFAULT NULL COMMENT 'Declara SICAP',
  `insalubridade` tinyint(3) DEFAULT NULL COMMENT 'Insalubridade',
  `decimo` tinyint(3) DEFAULT NULL COMMENT 'Recebe décimo',
  `id_vinculo` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Vínculo',
  `id_cat_sefip` int(11) DEFAULT NULL COMMENT 'Categoria SEFIP',
  `ocorrencia` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Ocorrência',
  `carga_horaria` decimal(15,2) DEFAULT NULL COMMENT 'Carga horária',
  `molestia` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Moléstia',
  `d_laudomolestia` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Moléstia data',
  `manad_tiponomeacao` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Tipo nomeação',
  `manad_numeronomeacao` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Número nomeação',
  `d_admissao` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Admissão data',
  `d_tempo` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `d_tempofim` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `d_beneficio` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `n_valorbaseinss` decimal(15,2) DEFAULT NULL COMMENT 'Valor base INSS',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  UNIQUE KEY `cad_sfuncional_dominio_id_cad_servidores_ano_mes_parcela` (`dominio`,`id_cad_servidores`,`ano`,`mes`,`parcela`),
  KEY `fk-cad_sfuncional-id_local_trabalho-cad_localtrabalho-id` (`id_local_trabalho`),
  KEY `fk-cad_sfuncional-id_cad_servidores-cad_servidores-id` (`id_cad_servidores`),
  CONSTRAINT `fk-cad_sfuncional-id_cad_servidores-cad_servidores-id` FOREIGN KEY (`id_cad_servidores`) REFERENCES `cad_servidores` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk-cad_sfuncional-id_local_trabalho-cad_localtrabalho-id` FOREIGN KEY (`id_local_trabalho`) REFERENCES `cad_localtrabalho` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6452 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `cad_smovimentacao` */

DROP TABLE IF EXISTS `cad_smovimentacao`;

CREATE TABLE `cad_smovimentacao` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID do registro',
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(3) NOT NULL DEFAULT 10,
  `dominio` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Domínio do cliente',
  `evento` int(11) NOT NULL DEFAULT 0 COMMENT 'Evento do registro',
  `created_at` int(11) NOT NULL COMMENT 'Registro em',
  `updated_at` int(11) NOT NULL COMMENT 'Atualização em',
  `id_cad_servidores` int(11) NOT NULL COMMENT 'ID do servidor',
  `codigo_afastamento` varchar(2) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Código do afastamento',
  `d_afastamento` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Data do afastamento',
  `codigo_retorno` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Código do retorno',
  `d_retorno` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Data do retorno',
  `motivo_desligamentorais` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Motivo do desligamento',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  UNIQUE KEY `cad_smovimentacao-dominio-servidor-cod_afast-d_afastamento` (`dominio`,`id_cad_servidores`,`codigo_afastamento`,`d_afastamento`)
) ENGINE=InnoDB AUTO_INCREMENT=751 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `cad_srecadastro` */

DROP TABLE IF EXISTS `cad_srecadastro`;

CREATE TABLE `cad_srecadastro` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID do registro',
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(3) NOT NULL DEFAULT 10,
  `dominio` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Domínio do cliente',
  `evento` int(11) NOT NULL DEFAULT 0 COMMENT 'Evento do registro',
  `created_at` int(11) NOT NULL COMMENT 'Registro em',
  `updated_at` int(11) NOT NULL COMMENT 'Atualização em',
  `id_cad_servidores` int(11) NOT NULL COMMENT 'ID do servidor',
  `id_user_recadastro` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Recadastro',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `fk-cad_srecadastro-id_cad_servidores-cad_servidores-id` (`id_cad_servidores`),
  CONSTRAINT `fk-cad_srecadastro-id_cad_servidores-cad_servidores-id` FOREIGN KEY (`id_cad_servidores`) REFERENCES `cad_servidores` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1025 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `fin_basefixa` */

DROP TABLE IF EXISTS `fin_basefixa`;

CREATE TABLE `fin_basefixa` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID do registro',
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(3) NOT NULL DEFAULT 10,
  `dominio` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Domínio do cliente',
  `evento` int(11) NOT NULL DEFAULT 0 COMMENT 'Evento do registro',
  `created_at` int(11) NOT NULL COMMENT 'Registro em',
  `updated_at` int(11) NOT NULL COMMENT 'Atualização em',
  `id_base_fixa` varchar(4) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Codigo base fixa',
  `nome_base_fixa` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Nome base fixa',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  UNIQUE KEY `fin_basefixa_dominio-id_base_fixa` (`dominio`,`id_base_fixa`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `fin_basefixaeventos` */

DROP TABLE IF EXISTS `fin_basefixaeventos`;

CREATE TABLE `fin_basefixaeventos` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID do registro',
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(3) NOT NULL DEFAULT 10,
  `dominio` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Domínio do cliente',
  `evento` int(11) NOT NULL DEFAULT 0 COMMENT 'Evento do registro',
  `created_at` int(11) NOT NULL COMMENT 'Registro em',
  `updated_at` int(11) NOT NULL COMMENT 'Atualização em',
  `id_fin_eventos` int(11) NOT NULL COMMENT 'Codigo evento',
  `id_fin_base_fixa` int(11) NOT NULL COMMENT 'Codigo base fixa',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  UNIQUE KEY `fin_basefixaeventos_dominio-id_evento-id_fin_base_fixa` (`dominio`,`id_fin_eventos`,`id_fin_base_fixa`),
  KEY `fk-fin_basefixaeventos-id_evento-fin_eventos-id` (`id_fin_eventos`),
  KEY `fk-fin_basefixaeventos-id_fin_base_fixa-fin_basefixa-id` (`id_fin_base_fixa`),
  CONSTRAINT `fk-fin_basefixaeventos-id_evento-fin_eventos-id` FOREIGN KEY (`id_fin_eventos`) REFERENCES `fin_eventos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk-fin_basefixaeventos-id_fin_base_fixa-fin_basefixa-id` FOREIGN KEY (`id_fin_base_fixa`) REFERENCES `fin_basefixa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `fin_basefixarefer` */

DROP TABLE IF EXISTS `fin_basefixarefer`;

CREATE TABLE `fin_basefixarefer` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID do registro',
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(3) NOT NULL DEFAULT 10,
  `dominio` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Domínio do cliente',
  `evento` int(11) NOT NULL DEFAULT 0 COMMENT 'Evento do registro',
  `created_at` int(11) NOT NULL COMMENT 'Registro em',
  `updated_at` int(11) NOT NULL COMMENT 'Atualização em',
  `id_fin_base_fixa` int(11) NOT NULL COMMENT 'Codigo base fixa',
  `id_fixa` varchar(4) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Codigo referencia',
  `descricao` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Descrição',
  `data` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Data',
  `valor` decimal(11,2) NOT NULL DEFAULT 0.00 COMMENT 'Valor base',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  UNIQUE KEY `fin_basefixarefer_dominio-id_fin_base_fixa-id_fixa` (`dominio`,`id_fin_base_fixa`,`id_fixa`),
  KEY `fk-fin_basefixarefer-id_fin_base_fixa-fin_basefixa-id` (`id_fin_base_fixa`),
  CONSTRAINT `fk-fin_basefixarefer-id_fin_base_fixa-fin_basefixa-id` FOREIGN KEY (`id_fin_base_fixa`) REFERENCES `fin_basefixa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `fin_eventos` */

DROP TABLE IF EXISTS `fin_eventos`;

CREATE TABLE `fin_eventos` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID do registro',
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(3) NOT NULL DEFAULT 10,
  `dominio` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Domínio do cliente',
  `evento` int(11) NOT NULL DEFAULT 0 COMMENT 'Evento do registro',
  `created_at` int(11) NOT NULL COMMENT 'Registro em',
  `updated_at` int(11) NOT NULL COMMENT 'Atualização em',
  `ev_root` int(11) NOT NULL COMMENT 'Pertence ao sistema',
  `id_evento` varchar(3) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Codigo evento',
  `evento_nome` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Evento',
  `tipo` char(1) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Tipo',
  `consignado` tinyint(1) NOT NULL COMMENT 'Consignado',
  `consignavel` tinyint(1) NOT NULL COMMENT 'Consignável',
  `deduzconsig` tinyint(1) NOT NULL COMMENT 'Deduz da consignação',
  `automatico` tinyint(1) NOT NULL COMMENT 'Automático',
  `vinculacao_dirf` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'DIRF',
  `i_prioridade` tinyint(1) NOT NULL COMMENT 'Prioridade',
  `fixo` tinyint(1) NOT NULL COMMENT 'Fixo',
  `sefip` tinyint(1) DEFAULT NULL COMMENT 'SEFIP',
  `rais` tinyint(1) DEFAULT NULL COMMENT 'RAIS',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  UNIQUE KEY `fin_eventos_dominio-id_evento` (`dominio`,`id_evento`)
) ENGINE=InnoDB AUTO_INCREMENT=225 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `fin_eventosbase` */

DROP TABLE IF EXISTS `fin_eventosbase`;

CREATE TABLE `fin_eventosbase` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID do registro',
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(3) NOT NULL DEFAULT 10,
  `dominio` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Domínio do cliente',
  `evento` int(11) NOT NULL DEFAULT 0 COMMENT 'Evento do registro',
  `created_at` int(11) NOT NULL COMMENT 'Registro em',
  `updated_at` int(11) NOT NULL COMMENT 'Atualização em',
  `id_fin_eventos` int(11) NOT NULL COMMENT 'Codigo evento',
  `id_fin_eventosbase` int(11) NOT NULL COMMENT 'Codigo evento base',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  UNIQUE KEY `fin_eventosbase_dominio-id_evento-id_eventos_base` (`dominio`,`id_fin_eventos`,`id_fin_eventosbase`),
  KEY `fk-fin_eventosbase-id_evento-fin_eventos-id` (`id_fin_eventos`),
  KEY `fk-fin_eventosbase-id_eventos_base-fin_eventos-id` (`id_fin_eventosbase`),
  CONSTRAINT `fk-fin_eventosbase-id_evento-fin_eventos-id` FOREIGN KEY (`id_fin_eventos`) REFERENCES `fin_eventos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk-fin_eventosbase-id_eventos_base-fin_eventos-id` FOREIGN KEY (`id_fin_eventosbase`) REFERENCES `fin_eventos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=232 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `fin_eventosdesconto` */

DROP TABLE IF EXISTS `fin_eventosdesconto`;

CREATE TABLE `fin_eventosdesconto` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID do registro',
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(3) NOT NULL DEFAULT 10,
  `dominio` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Domínio do cliente',
  `evento` int(11) NOT NULL DEFAULT 0 COMMENT 'Evento do registro',
  `created_at` int(11) NOT NULL COMMENT 'Registro em',
  `updated_at` int(11) NOT NULL COMMENT 'Atualização em',
  `id_fin_eventos` int(11) NOT NULL COMMENT 'Codigo evento',
  `id_fin_eventosdesconto` int(11) NOT NULL COMMENT 'Evento a descontar',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  UNIQUE KEY `fin_eventosdesconto_dominio-id_evento-id_fin_eventosdesconto` (`dominio`,`id_fin_eventos`,`id_fin_eventosdesconto`),
  KEY `fk-fin_eventosdesconto-id_evento-fin_eventos-id` (`id_fin_eventos`),
  KEY `fk-fin_eventosdesconto-id_fin_eventosdesconto-fin_eventos-id` (`id_fin_eventosdesconto`),
  CONSTRAINT `fk-fin_eventosdesconto-id_evento-fin_eventos-id` FOREIGN KEY (`id_fin_eventos`) REFERENCES `fin_eventos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk-fin_eventosdesconto-id_fin_eventosdesconto-fin_eventos-id` FOREIGN KEY (`id_fin_eventosdesconto`) REFERENCES `fin_eventos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `fin_eventospercentual` */

DROP TABLE IF EXISTS `fin_eventospercentual`;

CREATE TABLE `fin_eventospercentual` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID do registro',
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(3) NOT NULL DEFAULT 10,
  `dominio` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Domínio do cliente',
  `evento` int(11) NOT NULL DEFAULT 0 COMMENT 'Evento do registro',
  `created_at` int(11) NOT NULL COMMENT 'Registro em',
  `updated_at` int(11) NOT NULL COMMENT 'Atualização em',
  `id_fin_eventos` int(11) NOT NULL COMMENT 'Codigo evento',
  `id_eventos_percentual` varchar(4) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Código percentual',
  `data` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Data',
  `percentual` decimal(11,2) NOT NULL DEFAULT 0.00 COMMENT 'Percentual',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  UNIQUE KEY `fin_eventospercentual_dominio-id_f_evts-id_ev_perc` (`dominio`,`id_fin_eventos`,`id_eventos_percentual`),
  KEY `fk-fin_eventospercentual-id_evento-fin_eventos-id` (`id_fin_eventos`),
  CONSTRAINT `fk-fin_eventospercentual-id_evento-fin_eventos-id` FOREIGN KEY (`id_fin_eventos`) REFERENCES `fin_eventos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `fin_faixas` */

DROP TABLE IF EXISTS `fin_faixas`;

CREATE TABLE `fin_faixas` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID do registro',
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(3) NOT NULL DEFAULT 10,
  `dominio` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Domínio do cliente',
  `evento` int(11) NOT NULL DEFAULT 0 COMMENT 'Evento do registro',
  `created_at` int(11) NOT NULL COMMENT 'Registro em',
  `updated_at` int(11) NOT NULL COMMENT 'Atualização em',
  `tipo` tinyint(1) NOT NULL COMMENT 'Tipo da faixa',
  `data` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Data entra em vigor',
  `faixa` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Faixa',
  `v_final1` decimal(11,2) DEFAULT 0.00 COMMENT 'Valor final da faixa 1',
  `v_faixa1` decimal(11,2) DEFAULT 0.00 COMMENT 'Valor da faixa 1',
  `v_deduzir1` decimal(11,2) DEFAULT 0.00 COMMENT 'Deduzir da faixa 1',
  `v_final2` decimal(11,2) DEFAULT 0.00 COMMENT 'Valor final da faixa 2',
  `v_faixa2` decimal(11,2) DEFAULT 0.00 COMMENT 'Valor da faixa 2',
  `v_deduzir2` decimal(11,2) DEFAULT 0.00 COMMENT 'Deduzir da faixa 2',
  `v_final3` decimal(11,2) DEFAULT 0.00 COMMENT 'Valor final da faixa 3',
  `v_faixa3` decimal(11,2) DEFAULT 0.00 COMMENT 'Valor da faixa 3',
  `v_deduzir3` decimal(11,2) DEFAULT 0.00 COMMENT 'Deduzir da faixa 3',
  `v_final4` decimal(11,2) DEFAULT 0.00 COMMENT 'Valor final da faixa 4',
  `v_faixa4` decimal(11,2) DEFAULT 0.00 COMMENT 'Valor da faixa 4',
  `v_deduzir4` decimal(11,2) DEFAULT 0.00 COMMENT 'Deduzir da faixa 4',
  `v_final5` decimal(11,2) DEFAULT 0.00 COMMENT 'Valor final da faixa 5',
  `v_faixa5` decimal(11,2) DEFAULT 0.00 COMMENT 'Valor da faixa 5',
  `v_deduzir5` decimal(11,2) DEFAULT 0.00 COMMENT 'Deduzir da faixa 5',
  `deduzir_dependente` decimal(11,2) DEFAULT 0.00 COMMENT 'Deduzir por dependente',
  `salario_vigente` decimal(11,2) DEFAULT NULL COMMENT 'Alíquota',
  `inss_teto` decimal(11,2) DEFAULT 0.00 COMMENT 'INSS teto',
  `inss_patronal` decimal(11,2) DEFAULT 0.00 COMMENT 'INSS patronal',
  `inss_rat` decimal(11,2) DEFAULT 0.00 COMMENT 'INSS Rat',
  `inss_fap` decimal(11,2) DEFAULT 0.00 COMMENT 'INSS Fap',
  `rpps_patronal` decimal(11,2) DEFAULT 0.00 COMMENT 'rpps patronal',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  UNIQUE KEY `fin_faixas_dominio-tipo-faixa-data` (`dominio`,`tipo`,`faixa`,`data`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `fin_parametros` */

DROP TABLE IF EXISTS `fin_parametros`;

CREATE TABLE `fin_parametros` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID do registro',
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(3) NOT NULL DEFAULT 10,
  `dominio` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Domínio do cliente',
  `evento` int(11) NOT NULL DEFAULT 0 COMMENT 'Evento do registro',
  `created_at` int(11) NOT NULL COMMENT 'Registro em',
  `updated_at` int(11) NOT NULL COMMENT 'Atualização em',
  `ano` varchar(4) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Ano',
  `mes` varchar(2) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Mês',
  `parcela` varchar(3) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Parcela',
  `ano_informacao` varchar(4) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Ano informação',
  `mes_informacao` varchar(2) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Mês informação',
  `parcela_informacao` varchar(3) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Parcela informação',
  `descricao` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Descrição',
  `situacao` int(1) NOT NULL COMMENT 'Situação',
  `d_situacao` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Data situação',
  `mensagem` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Mensagem',
  `mensagem_aniversario` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Mensagem aniversário',
  `manad_tipofolha` tinyint(1) NOT NULL COMMENT 'Manad tipo folha',
  `tgf` int(11) DEFAULT NULL COMMENT 'Tempo de geração da folha em segundos',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  UNIQUE KEY `fin_parametros_dominio-mes-ano-parcela` (`dominio`,`mes`,`ano`,`parcela`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `fin_referencias` */

DROP TABLE IF EXISTS `fin_referencias`;

CREATE TABLE `fin_referencias` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID do registro',
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(3) NOT NULL DEFAULT 10,
  `dominio` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Domínio do cliente',
  `evento` int(11) NOT NULL DEFAULT 0 COMMENT 'Evento do registro',
  `created_at` int(11) NOT NULL COMMENT 'Registro em',
  `updated_at` int(11) NOT NULL COMMENT 'Atualização em',
  `id_pccs` int(11) NOT NULL COMMENT 'PCCS',
  `id_classe` int(11) NOT NULL COMMENT 'Classe',
  `referencia` int(11) NOT NULL COMMENT 'Referência',
  `valor` decimal(11,2) NOT NULL COMMENT 'Valor',
  `data` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Data',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  UNIQUE KEY `fin_referencias_dominio-id_pccs-cad_classes-referencia` (`dominio`,`id_pccs`,`id_classe`,`referencia`)
) ENGINE=InnoDB AUTO_INCREMENT=2199 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `fin_rubricas` */

DROP TABLE IF EXISTS `fin_rubricas`;

CREATE TABLE `fin_rubricas` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID do registro',
  `status` tinyint(3) NOT NULL DEFAULT 10,
  `dominio` varchar(255) CHARACTER SET latin1 NOT NULL COMMENT 'Domínio do cliente',
  `evento` int(11) NOT NULL DEFAULT 1,
  `created_at` int(11) NOT NULL COMMENT 'Registro em',
  `updated_at` int(11) NOT NULL COMMENT 'Atualização em',
  `id_cad_servidores` int(11) DEFAULT NULL COMMENT 'Servidor',
  `id_fin_eventos` int(11) DEFAULT NULL COMMENT 'Rúbrica',
  `slug` varchar(255) NOT NULL,
  `ano` varchar(4) CHARACTER SET latin1 NOT NULL COMMENT 'Ano',
  `mes` varchar(2) CHARACTER SET latin1 NOT NULL COMMENT 'Mês',
  `parcela` varchar(3) CHARACTER SET latin1 NOT NULL COMMENT 'Parcela',
  `referencia` varchar(255) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Referência',
  `valor_baseespecial` float(11,2) NOT NULL DEFAULT 0.00 COMMENT 'Valor base especial',
  `valor_base` float(11,2) NOT NULL DEFAULT 0.00 COMMENT 'Valor base',
  `valor_basefixa` float(11,2) NOT NULL DEFAULT 0.00 COMMENT 'Valor base fixa',
  `valor_desconto` float(11,2) NOT NULL DEFAULT 0.00 COMMENT 'Desconto',
  `valor_percentual` float(11,2) NOT NULL DEFAULT 0.00 COMMENT 'Percentual',
  `valor_saldo` float(11,2) NOT NULL DEFAULT 0.00 COMMENT 'Saldo',
  `valor` float(11,2) NOT NULL DEFAULT 0.00 COMMENT 'Provento|Desconto',
  `valor_patronal` float(11,2) NOT NULL DEFAULT 0.00 COMMENT 'Valor patronal',
  `valor_maternidade` float(11,2) NOT NULL DEFAULT 0.00 COMMENT 'Valor maternidade',
  `prazo` int(3) NOT NULL DEFAULT 1 COMMENT 'Parcela atual',
  `prazot` int(3) NOT NULL DEFAULT 1 COMMENT 'Parcela final',
  PRIMARY KEY (`id`),
  UNIQUE KEY `fin_rubricas_dominio-serv-evtos-ano-mes-parcela` (`dominio`,`id_cad_servidores`,`id_fin_eventos`,`ano`,`mes`,`parcela`),
  KEY `fk-fin_rubricas-id_fin_eventos-fin_eventos-id` (`id_fin_eventos`),
  KEY `fk-fin_rubricas-id_cad_servidores-cad_servidores-id` (`id_cad_servidores`),
  CONSTRAINT `fk-fin_rubricas-id_cad_servidores-cad_servidores-id` FOREIGN KEY (`id_cad_servidores`) REFERENCES `cad_servidores` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk-fin_rubricas-id_fin_eventos-fin_eventos-id` FOREIGN KEY (`id_fin_eventos`) REFERENCES `fin_eventos` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=450300 DEFAULT CHARSET=utf8mb4;

/*Table structure for table `fin_sfuncional` */

DROP TABLE IF EXISTS `fin_sfuncional`;

CREATE TABLE `fin_sfuncional` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID do registro',
  `slug` varchar(255) NOT NULL,
  `status` tinyint(3) NOT NULL DEFAULT 10,
  `dominio` varchar(255) CHARACTER SET latin1 NOT NULL COMMENT 'Domínio do cliente',
  `created_at` int(11) NOT NULL COMMENT 'Registro em',
  `updated_at` int(11) NOT NULL COMMENT 'Atualização em',
  `id_cad_servidores` int(11) NOT NULL COMMENT 'Servidor',
  `evento` int(11) NOT NULL DEFAULT 0,
  `ano` varchar(4) CHARACTER SET latin1 NOT NULL COMMENT 'Ano',
  `mes` varchar(2) CHARACTER SET latin1 NOT NULL COMMENT 'Mês',
  `parcela` varchar(3) CHARACTER SET latin1 NOT NULL COMMENT 'Parcela',
  `situacao` tinyint(1) DEFAULT NULL COMMENT 'Situação cadastral',
  `situacaofuncional` tinyint(1) DEFAULT NULL COMMENT 'Situação funcional',
  `id_cad_cargos` int(11) DEFAULT NULL COMMENT 'Cargo',
  `id_cad_centros` int(11) DEFAULT NULL COMMENT 'Centro',
  `id_cad_departamentos` int(11) DEFAULT NULL COMMENT 'Locação',
  `id_pccs` int(11) DEFAULT NULL COMMENT 'PCCS',
  `desconta_irrf` tinyint(1) DEFAULT NULL COMMENT 'Desconta IRRF',
  `tp_previdencia` tinyint(1) DEFAULT NULL COMMENT 'Tipo da previdência (2 = RPPS e 3 - INSS)',
  `desconta_sindicato` tinyint(1) DEFAULT NULL COMMENT 'Desconta sindicato',
  `enio` tinyint(1) DEFAULT NULL COMMENT 'Anuênio, Triênio, Quinquênio ou Decênio',
  `lanca_anuenio` tinyint(1) DEFAULT NULL COMMENT 'Anuênio',
  `lanca_trienio` tinyint(1) DEFAULT NULL COMMENT 'Triênio',
  `lanca_quinquenio` tinyint(1) DEFAULT NULL COMMENT 'Quinquênio',
  `lanca_decenio` tinyint(1) DEFAULT NULL COMMENT 'Decênio',
  `lanca_salario` tinyint(1) DEFAULT NULL COMMENT 'Lança salário',
  `lanca_funcao` tinyint(1) DEFAULT NULL COMMENT 'Lança função',
  `n_faltas` int(3) DEFAULT NULL COMMENT 'Dias de falta',
  `decimo_aniv` tinyint(1) DEFAULT NULL COMMENT '13º no aniversário',
  `n_horaaula` int(3) DEFAULT NULL COMMENT 'Horas aula',
  `n_adnoturno` int(3) DEFAULT NULL COMMENT 'Adicional noturno',
  `n_hextra` int(3) DEFAULT NULL COMMENT 'Horas ewxtras',
  `categoria_receita` char(4) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Categoria receita',
  `previdencia` tinyint(1) DEFAULT NULL COMMENT '??? previdência',
  `tipobeneficio` tinyint(1) DEFAULT NULL COMMENT '??? tipo benif',
  `d_beneficio` varchar(10) CHARACTER SET latin1 DEFAULT NULL COMMENT '??? d_beneficio',
  `retorno_ocorrencia` varchar(2) CHARACTER SET latin1 DEFAULT NULL COMMENT '??? retorno_ocorr',
  `retorno_data` varchar(10) CHARACTER SET latin1 DEFAULT NULL COMMENT '??? retorno_data',
  `retorno_valor` float(11,2) DEFAULT NULL COMMENT '??? retorno_valor',
  `retorno_documento` varchar(50) CHARACTER SET latin1 DEFAULT NULL COMMENT '??? retorno_documento',
  `ponto` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `fin_sfuncional_slug_unique` (`slug`),
  UNIQUE KEY `fin_sfuncional_dominio-serv-ano-mes-parcela` (`dominio`,`id_cad_servidores`,`ano`,`mes`,`parcela`),
  KEY `fk-fin_sfuncional-servidores-cad_servidores-id` (`id_cad_servidores`),
  KEY `fk-fin_sfuncional-cargos-cad_cargos-id` (`id_cad_cargos`),
  KEY `fk-fin_sfuncional-centros-cad_centros-id` (`id_cad_centros`),
  KEY `fk-fin_sfuncional-departamentos-cad_departamentos-id` (`id_cad_departamentos`),
  CONSTRAINT `fk-fin_sfuncional-cargos-cad_cargos-id` FOREIGN KEY (`id_cad_cargos`) REFERENCES `cad_cargos` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk-fin_sfuncional-centros-cad_centros-id` FOREIGN KEY (`id_cad_centros`) REFERENCES `cad_centros` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk-fin_sfuncional-departamentos-cad_departamentos-id` FOREIGN KEY (`id_cad_departamentos`) REFERENCES `cad_departamentos` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk-fin_sfuncional-servidores-cad_servidores-id` FOREIGN KEY (`id_cad_servidores`) REFERENCES `cad_servidores` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=148750 DEFAULT CHARSET=utf8mb4;

/*Table structure for table `financeiro` */

DROP TABLE IF EXISTS `financeiro`;

CREATE TABLE `financeiro` (
  `idservidor` varchar(8) NOT NULL,
  `dominio` varchar(255) NOT NULL,
  `idevento` varchar(3) NOT NULL,
  `ano` varchar(4) NOT NULL,
  `mes` varchar(2) NOT NULL,
  `parcela` varchar(3) NOT NULL,
  `referencia` varchar(10) DEFAULT NULL,
  `n_baseespecial` decimal(15,2) DEFAULT NULL,
  `n_base` decimal(15,2) DEFAULT NULL,
  `n_basefixa` decimal(15,2) DEFAULT NULL,
  `n_desconto` decimal(15,2) DEFAULT NULL,
  `n_percentual` decimal(15,2) DEFAULT NULL,
  `n_saldo` decimal(15,2) DEFAULT NULL,
  `n_valor` decimal(15,2) DEFAULT NULL,
  `n_valorpatronal` decimal(15,2) DEFAULT NULL,
  `n_valor_maternidade` decimal(15,2) DEFAULT NULL,
  `i_prazo` int(11) DEFAULT NULL,
  `i_prazot` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Table structure for table `local_params` */

DROP TABLE IF EXISTS `local_params`;

CREATE TABLE `local_params` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID do registro',
  `slug` varchar(255) NOT NULL,
  `status` tinyint(3) NOT NULL DEFAULT 10,
  `dominio` varchar(255) NOT NULL COMMENT 'Domínio do cliente',
  `evento` int(11) NOT NULL DEFAULT 0 COMMENT 'Evento do registro',
  `created_at` int(11) NOT NULL COMMENT 'Registro em',
  `updated_at` int(11) NOT NULL COMMENT 'Atualização em',
  `grupo` varchar(255) DEFAULT NULL COMMENT 'Grupo do parâmetro',
  `parametro` text DEFAULT NULL COMMENT 'Parâmetro',
  `label` text DEFAULT NULL COMMENT 'Label',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

/*Table structure for table `mensal` */

DROP TABLE IF EXISTS `mensal`;

CREATE TABLE `mensal` (
  `idservidor` varchar(8) NOT NULL,
  `dominio` varchar(255) NOT NULL,
  `mes` varchar(2) NOT NULL,
  `ano` varchar(4) NOT NULL,
  `situacao` varchar(20) DEFAULT NULL,
  `idcargo` varchar(4) DEFAULT NULL,
  `idcentro` varchar(4) DEFAULT NULL,
  `idlocacao` varchar(4) DEFAULT NULL,
  `idpccs` varchar(4) DEFAULT NULL,
  `desconta_irrf` varchar(1) DEFAULT NULL,
  `desconta_inss` varchar(1) DEFAULT NULL,
  `desconta_rpps` varchar(1) DEFAULT NULL,
  `lanca_anuenio` varchar(1) DEFAULT NULL,
  `lanca_trienio` varchar(1) DEFAULT NULL,
  `lanca_quinquenio` varchar(1) DEFAULT NULL,
  `lanca_decenio` varchar(1) DEFAULT NULL,
  `lanca_salario` varchar(1) DEFAULT NULL,
  `lanca_funcao` varchar(1) DEFAULT NULL,
  `n_faltas` decimal(15,2) DEFAULT NULL,
  `previdencia` varchar(1) DEFAULT NULL,
  `decimo_aniv` varchar(1) DEFAULT NULL,
  `tipobeneficio` varchar(25) DEFAULT NULL,
  `situacaofuncional` varchar(25) DEFAULT NULL,
  `desconta_sindicato` varchar(3) DEFAULT NULL,
  `parcela` varchar(3) NOT NULL,
  `d_beneficio` date DEFAULT NULL,
  `retorno_ocorrencia` varchar(2) DEFAULT NULL,
  `retorno_data` date DEFAULT NULL,
  `retorno_valor` decimal(15,2) DEFAULT NULL,
  `retorno_documento` varchar(20) DEFAULT NULL,
  `n_horaaula` decimal(15,2) DEFAULT NULL,
  `n_adnoturno` decimal(15,2) DEFAULT NULL,
  `n_hextra` decimal(15,2) DEFAULT NULL,
  `categoria_receita` char(4) DEFAULT NULL,
  `ponto` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Table structure for table `migration` */

DROP TABLE IF EXISTS `migration`;

CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Table structure for table `orgao` */

DROP TABLE IF EXISTS `orgao`;

CREATE TABLE `orgao` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID do registro',
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(3) NOT NULL DEFAULT 10,
  `dominio` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Domínio do cliente',
  `evento` int(11) NOT NULL DEFAULT 0 COMMENT 'Evento do registro',
  `created_at` int(11) NOT NULL COMMENT 'Registro em',
  `updated_at` int(11) NOT NULL COMMENT 'Atualização em',
  `orgao_cliente` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Nome orgão snake_case',
  `orgao` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Nome do cliente',
  `cnpj` varchar(14) COLLATE utf8_unicode_ci NOT NULL COMMENT 'CNPJ do cliente',
  `url_logo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Url da logo do cliente',
  `cep` varchar(8) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Cep',
  `logradouro` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Logradouro',
  `numero` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Número',
  `complemento` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Complemento',
  `bairro` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Bairro',
  `cidade` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Cidade',
  `uf` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'AL' COMMENT 'Estado',
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Email',
  `telefone` varchar(14) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Telefone',
  `codigo_fpas` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Código FPAS',
  `codigo_gps` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Código GPS',
  `codigo_cnae` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Código CNAE',
  `codigo_ibge` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Código IBGE',
  `codigo_fgts` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Código FGTS',
  `mes_descsindical` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Mês desconto sindical',
  `cpf_responsavel_dirf` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'CPF Resp Dirf',
  `nome_responsavel_dirf` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Nome Resp Dirf',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  UNIQUE KEY `orgao_dominio` (`dominio`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `orgao_resp` */

DROP TABLE IF EXISTS `orgao_resp`;

CREATE TABLE `orgao_resp` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID do registro',
  `id_orgao` int(11) NOT NULL COMMENT 'Orgão',
  `status` tinyint(3) NOT NULL DEFAULT 10,
  `evento` int(11) NOT NULL COMMENT 'Evento do registro',
  `created_at` int(11) NOT NULL COMMENT 'Registro em',
  `updated_at` int(11) NOT NULL COMMENT 'Atualização em',
  `cpf_gestor` varchar(11) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Cpf do gestor',
  `nome_gestor` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Nome do gestor',
  `d_nascimento` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Nascimento do gestor',
  `cep` varchar(8) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Cep',
  `logradouro` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Logradouro',
  `numero` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Número',
  `complemento` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Complemento',
  `bairro` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Bairro',
  `cidade` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Cidade',
  `uf` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'AL' COMMENT 'Estado',
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Email',
  `telefone` varchar(14) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Telefone',
  PRIMARY KEY (`id`),
  UNIQUE KEY `un-orgao_resp-id_orgao-cpf_gestor` (`id_orgao`,`cpf_gestor`),
  CONSTRAINT `fk-orgao_resp-id_orgao-orgao-id` FOREIGN KEY (`id_orgao`) REFERENCES `orgao` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `orgao_ua` */

DROP TABLE IF EXISTS `orgao_ua`;

CREATE TABLE `orgao_ua` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID do registro',
  `id_orgao` int(11) NOT NULL,
  `status` tinyint(3) NOT NULL DEFAULT 10,
  `evento` int(11) NOT NULL COMMENT 'Evento do registro',
  `created_at` int(11) NOT NULL COMMENT 'Registro em',
  `updated_at` int(11) NOT NULL COMMENT 'Atualização em',
  `codigo` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Código',
  `cnpj` varchar(14) COLLATE utf8_unicode_ci NOT NULL COMMENT 'CNPJ',
  `nome` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Nome',
  PRIMARY KEY (`id`),
  UNIQUE KEY `un-orgao_ua-id_orgao-codigo-cnpj` (`id_orgao`,`codigo`,`cnpj`),
  CONSTRAINT `fk-orgao_ua-id_orgao-orgao-id` FOREIGN KEY (`id_orgao`) REFERENCES `orgao` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `sis_events` */

DROP TABLE IF EXISTS `sis_events`;

CREATE TABLE `sis_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID do registro',
  `slug` varchar(255) NOT NULL,
  `status` tinyint(3) NOT NULL DEFAULT 10,
  `dominio` varchar(255) NOT NULL COMMENT 'Domínio do cliente',
  `created_at` int(11) NOT NULL COMMENT 'Registro em',
  `updated_at` int(11) NOT NULL COMMENT 'Atualização em',
  `evento` text DEFAULT NULL COMMENT 'Evento do registro',
  `classevento` varchar(50) DEFAULT NULL COMMENT 'Classe',
  `tabela_bd` varchar(50) DEFAULT NULL COMMENT 'Tabela do BD',
  `id_registro` int(11) DEFAULT NULL COMMENT 'Id do Registro',
  `ip` varchar(50) DEFAULT NULL COMMENT 'Tabela do BD',
  `geo_lt` varchar(50) DEFAULT NULL COMMENT 'Geo Latitude',
  `geo_ln` varchar(50) DEFAULT NULL COMMENT 'Geo Longitude',
  `id_user` int(11) NOT NULL COMMENT 'Id do Registro',
  `username` varchar(255) DEFAULT NULL COMMENT 'Username',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=6885 DEFAULT CHARSET=utf8mb4;

/* Function  structure for function  `getDFPcc` */

/*!50003 DROP FUNCTION IF EXISTS `getDFPcc` */;
DELIMITER $$

/*!50003 CREATE FUNCTION `getDFPcc`(v_dominio VARCHAR(255),v_ano VARCHAR(4),v_mes VARCHAR(2),
	v_parcela VARCHAR(3),v_id_cad_servidor VARCHAR(255)) RETURNS int(11)
    COMMENT 'Retorna o PCC de um servidor'
BEGIN
    DECLARE v_id_cad_pccs INT(11);    
	SELECT fin_sfuncional.id_cad_pccs
	FROM fin_sfuncional
	WHERE fin_sfuncional.dominio = v_dominio AND fin_sfuncional.ano = v_ano 
	AND fin_sfuncional.mes = v_mes AND fin_sfuncional.parcela = v_parcela 
	AND fin_sfuncional.id_cad_servidores = v_id_cad_servidor
	INTO v_id_cad_pccs;
    RETURN v_id_cad_pccs;
    END */$$
DELIMITER ;

/* Function  structure for function  `getDiasPagarVctoBasico` */

/*!50003 DROP FUNCTION IF EXISTS `getDiasPagarVctoBasico` */;
DELIMITER $$

/*!50003 CREATE FUNCTION `getDiasPagarVctoBasico`(ics INT(11),ano_f CHAR(4),mes_f CHAR(2)) RETURNS varchar(255) CHARSET latin1
    DETERMINISTIC
BEGIN
    DECLARE dias
    , dias_p INT(2);
    DECLARE d_afastamento, d_limite
    , d_admissao
    , d_retorno
    , udm0 
    , udm1  DATE;
    DECLARE codigo_afastamento VARCHAR(2);
    DECLARE r VARCHAR(255);
    
    SET dias_p = 15;
    
    SET udm0 = LAST_DAY(DATE_SUB(CAST(CONCAT(ano_f,'-',mes_f,'-01') AS DATE), INTERVAL 1 MONTH));
    
    SET udm1 = LAST_DAY(CAST(CONCAT(ano_f,'-',mes_f,'-01') AS DATE));
	SELECT STR_TO_DATE(cad_servidores.d_admissao, '%d/%m/%Y') AS d_admissao,	
		STR_TO_DATE(cad_smovimentacao.d_afastamento, '%d/%m/%Y') AS d_afastamento,
		STR_TO_DATE(cad_smovimentacao.d_retorno, '%d/%m/%Y') AS d_retorno,
		cad_smovimentacao.codigo_afastamento AS codigo_afastamento
		FROM cad_servidores
		LEFT JOIN cad_smovimentacao ON cad_servidores.id = cad_smovimentacao.id_cad_servidores 
			AND cad_servidores.dominio = cad_smovimentacao.dominio
		WHERE cad_servidores.id = ics
		ORDER BY STR_TO_DATE(cad_smovimentacao.d_afastamento, '%d/%m/%Y') DESC
		LIMIT 1 
		INTO d_admissao, d_afastamento, d_retorno, codigo_afastamento;
	IF codigo_afastamento = 'P1'  THEN		
		SET dias = DAY(d_afastamento) + dias_p;
		SET d_limite = DATE_ADD(d_afastamento, INTERVAL dias_p DAY);
		
		IF d_limite >= udm1 THEN
			
			SET dias = DAY(udm1);
			SET r = '0';
		
		ELSEIF d_limite < udm1 AND 
		CONCAT(YEAR(d_limite),MONTH(d_limite)) = CONCAT(YEAR(udm1),MONTH(udm1)) THEN
			SET dias = DAY(d_limite);
			SET r = '1';
		
		ELSEIF d_limite < udm1 AND 
		CONCAT(YEAR(d_limite),MONTH(d_limite)) != CONCAT(YEAR(udm1),MONTH(udm1)) THEN
			SET dias = 0;
			SET r = '2';
		END IF;
		
		
		IF d_retorno IS NOT NULL 
		AND CONCAT(YEAR(d_retorno),MONTH(d_retorno)) = CONCAT(YEAR(udm1),MONTH(udm1)) THEN
			SET dias = dias + DATEDIFF(udm1,d_retorno);
			SET r = '3';
		ELSEIF d_retorno IS NOT NULL AND d_retorno < udm1 THEN
			SET dias = DAY(udm1);
			SET r = '4';
		END IF;
	ELSEIF codigo_afastamento = 'X'  THEN		
		SET dias = DAY(d_afastamento);
		SET d_limite = d_afastamento;
		
		IF d_limite >= udm1 THEN
			
			SET dias = DAY(udm1);
			SET r = '5';
		
		ELSEIF d_limite < udm1 AND 
		CONCAT(YEAR(d_limite),MONTH(d_limite)) = CONCAT(YEAR(udm1),MONTH(udm1)) THEN
			SET dias = DAY(d_limite);
			SET r = '6';
		
		ELSEIF d_limite < udm1 AND 
		CONCAT(YEAR(d_limite),MONTH(d_limite)) != CONCAT(YEAR(udm1),MONTH(udm1)) THEN
			SET dias = 0;
			SET r = '7';
		END IF;
		SET d_retorno = DATE_SUB(d_retorno, INTERVAL 1 DAY);
		
		
		IF d_retorno IS NOT NULL 
		AND CONCAT(YEAR(d_retorno),MONTH(d_retorno)) = CONCAT(YEAR(udm1),MONTH(udm1)) THEN
			SET dias = dias + DATEDIFF(udm1,d_retorno);
			SET r = '8';
		ELSEIF d_retorno IS NOT NULL AND d_retorno < udm1 THEN
			SET dias = DAY(udm1);
			SET r = '9';
		END IF;
	
	
	ELSEIF codigo_afastamento IS NOT NULL && codigo_afastamento != 'P1'
	 && codigo_afastamento != 'Q1' && codigo_afastamento != 'X'  THEN		
		SET dias = DAY(d_afastamento);
		SET d_limite = d_afastamento;
		
		IF d_limite > udm1 THEN
			
			SET dias = DAY(udm1);
			SET r = 'Demiss0';
		
		ELSEIF d_limite <= udm1 AND 
		CONCAT(YEAR(d_limite),MONTH(d_limite)) = CONCAT(YEAR(udm1),MONTH(udm1)) THEN
			SET dias = DAY(d_limite);
			SET r = 'Demiss1';
		
		ELSEIF d_limite < udm1 AND 
		CONCAT(YEAR(d_limite),MONTH(d_limite)) != CONCAT(YEAR(udm1),MONTH(udm1)) THEN
			SET dias = 0;
			SET r = 'Demiss2';
		END IF;
	ELSEIF LAST_DAY(d_admissao) = udm1 THEN
	
		SET dias = DAY(udm1) - DAY(d_admissao) + 1;
		SET r = '10';
	ELSE
	
		SET dias = DAY(udm1);
		SET r = '11';
	END IF;
	
RETURN dias;
END */$$
DELIMITER ;

/* Function  structure for function  `getEventoSistema` */

/*!50003 DROP FUNCTION IF EXISTS `getEventoSistema` */;
DELIMITER $$

/*!50003 CREATE FUNCTION `getEventoSistema`(v_dominio VARCHAR(255),v_evento TEXT, 
	v_classevento VARCHAR(255), v_tabela_bd VARCHAR(255), v_id_registro INT(11), v_id_user INT(11)) RETURNS int(11)
    COMMENT 'Auxilia no registro de um evento de sistema'
BEGIN
		DECLARE id_evento INT(11);
		DECLARE ip, geo_td, geo_ln VARCHAR(255);
		SELECT er.ip,er.geo_lt,er.geo_ln INTO ip, geo_td, geo_ln FROM sis_events er WHERE er.id_user = v_id_user ORDER BY er.id DESC LIMIT 1;
		SET id_evento = (SELECT MAX(id) FROM sis_events) + 1;
		INSERT INTO sis_events (
			id,slug,STATUS,dominio,evento,classevento,tabela_bd,id_registro,
			ip,geo_lt,geo_ln,id_user,username,created_at,updated_at
		) VALUES (
			id_evento,SHA(id_evento),10,v_dominio,v_evento,v_classevento,v_tabela_bd,v_id_registro,
			ip, geo_td, geo_ln,
			v_id_user,(SELECT username FROM mgfolha_folha.user WHERE id = v_id_user),UNIX_TIMESTAMP(NOW()),UNIX_TIMESTAMP(NOW())
		);
		return id_evento;
	END */$$
DELIMITER ;

/* Function  structure for function  `getFichaFinanceiraACacimbinhas` */

/*!50003 DROP FUNCTION IF EXISTS `getFichaFinanceiraACacimbinhas` */;
DELIMITER $$

/*!50003 CREATE FUNCTION `getFichaFinanceiraACacimbinhas`(v_dominio VARCHAR(255),v_ano VARCHAR(4),v_mes VARCHAR(2),
	v_parcela VARCHAR(3),v_id_cad_servidor VARCHAR(255), v_like VARCHAR(255)) RETURNS decimal(11,2)
    COMMENT 'Retorna o valor bruto da folha de um servidor'
BEGIN
	DECLARE r_valor DECIMAL(11,2);
	-- salário base
	IF v_like = 'base' THEN
		SELECT SUM(base.valor) INTO r_valor FROM fin_rubricas AS base
		JOIN fin_eventos fe ON fe.id = base.id_fin_eventos
		WHERE base.dominio = v_dominio AND fe.tipo = 0 AND fe.evento_nome LIKE '%vencimento%' 
		AND base.`id_cad_servidores` = v_id_cad_servidor AND base.ano = CAST(v_ano AS INTEGER) AND 
		base.mes = CAST(v_mes AS INTEGER) AND base.parcela = CAST(v_parcela AS INTEGER);
	ELSEIF v_like = 'bruto' THEN
		-- salário bruto
		SELECT SUM(base.valor)base INTO r_valor FROM fin_rubricas AS base
		JOIN fin_eventos fe ON fe.id = base.id_fin_eventos
		WHERE base.dominio = v_dominio and fe.tipo = 0 AND base.`id_cad_servidores` = v_id_cad_servidor AND base.ano = CAST(v_ano AS INTEGER) AND 
		base.mes = CAST(v_mes AS INTEGER) AND base.parcela = CAST(v_parcela AS INTEGER);
	ELSEIF v_like = 'descontos' THEN
		-- descontos
		SELECT SUM(base.valor)base INTO r_valor FROM fin_rubricas AS base
		JOIN fin_eventos fe ON fe.id = base.id_fin_eventos
		WHERE base.dominio = v_dominio and fe.tipo = 1 AND base.`id_cad_servidores` = v_id_cad_servidor AND base.ano = CAST(v_ano AS INTEGER) AND 
		base.mes = CAST(v_mes AS INTEGER) AND base.parcela = CAST(v_parcela AS INTEGER);
	ELSEIF v_like = 'inss' THEN
		-- INSS
		SELECT SUM(base.valor)base INTO r_valor FROM fin_rubricas AS base
		JOIN fin_eventos fe ON fe.id = base.id_fin_eventos
		WHERE base.dominio = v_dominio and fe.tipo = 1 AND fe.evento_nome LIKE '%inss%' AND fe.tipo = 1 AND base.`id_cad_servidores` = v_id_cad_servidor AND 
		base.ano = CAST(v_ano AS INTEGER) AND base.mes = CAST(v_mes AS INTEGER) AND base.parcela = CAST(v_parcela AS INTEGER);
	ELSEIF v_like = 'rpps' THEN
		-- RPPS
		SELECT SUM(base.valor)base INTO r_valor FROM fin_rubricas AS base
		JOIN fin_eventos fe ON fe.id = base.id_fin_eventos
		WHERE base.dominio = v_dominio and fe.tipo = 1 AND fe.evento_nome LIKE '%imprec%' AND fe.tipo = 1 AND base.`id_cad_servidores` = v_id_cad_servidor AND 
		base.ano = CAST(v_ano AS INTEGER) AND base.mes = CAST(v_mes AS INTEGER) AND base.parcela = CAST(v_parcela AS INTEGER);
	ELSEIF v_like = 'irrf' THEN
		-- IRPF
		SELECT SUM(base.valor)base INTO r_valor FROM fin_rubricas AS base
		JOIN fin_eventos fe ON fe.id = base.id_fin_eventos
		WHERE base.dominio = v_dominio and fe.tipo = 1 AND fe.evento_nome LIKE '%irpf%' AND fe.tipo = 1 AND base.`id_cad_servidores` = v_id_cad_servidor AND 
		base.ano = CAST(v_ano AS INTEGER) AND base.mes = CAST(v_mes AS INTEGER) AND base.parcela = CAST(v_parcela AS INTEGER);
	END IF;
	
	if r_valor is null then 
		SET r_valor = 0.00; 
	end if;
	RETURN r_valor;
    END */$$
DELIMITER ;

/* Function  structure for function  `getFinEventosPercentual` */

/*!50003 DROP FUNCTION IF EXISTS `getFinEventosPercentual` */;
DELIMITER $$

/*!50003 CREATE FUNCTION `getFinEventosPercentual`(v_dominio varchar(255), v_id_fin_eventos int) RETURNS decimal(11,2)
    COMMENT 'Retorna o percentual do evento'
BEGIN
	declare r_percentual decimal(11,2);
	SELECT fin_eventospercentual.percentual
		into r_percentual FROM fin_eventospercentual 
		WHERE fin_eventospercentual.id_fin_eventos = v_id_fin_eventos
		AND fin_eventospercentual.dominio = v_dominio 
		ORDER BY STR_TO_DATE(fin_eventospercentual.data, '%d/%m/%Y') DESC LIMIT 1;
	RETURN r_percentual;
    END */$$
DELIMITER ;

/* Function  structure for function  `getFinRubricaPrazo` */

/*!50003 DROP FUNCTION IF EXISTS `getFinRubricaPrazo` */;
DELIMITER $$

/*!50003 CREATE FUNCTION `getFinRubricaPrazo`(prazo int, prazot int) RETURNS int(11)
    COMMENT 'Retorna o prazo inicial baseado nos prazos inicial e final passados'
BEGIN
	declare prazo_final int(3); 
	if prazot = prazo then
		set prazo_final = prazo;
	elseIF prazot > prazo THEN
		SET prazo_final = prazo + 1;
	end if;
	return prazo_final;
    END */$$
DELIMITER ;

/* Function  structure for function  `getFinRubricaPrazoT` */

/*!50003 DROP FUNCTION IF EXISTS `getFinRubricaPrazoT` */;
DELIMITER $$

/*!50003 CREATE FUNCTION `getFinRubricaPrazoT`(prazo int, prazot int) RETURNS int(11)
    COMMENT 'Retorna o prazo final baseado nos prazos inicial e final passados'
BEGIN
	declare prazo_final int(3); 
	if (prazot = prazo) && ((prazot = '999') || (prazot = '1')) then
		set prazo_final = prazot;
	elseIF prazot > prazo THEN
		SET prazo_final = prazo + 1;
	end if;
	return prazo_final;
    END */$$
DELIMITER ;

/* Function  structure for function  `getFinRubricasEvtValor` */

/*!50003 DROP FUNCTION IF EXISTS `getFinRubricasEvtValor` */;
DELIMITER $$

/*!50003 CREATE FUNCTION `getFinRubricasEvtValor`(v_dominio VARCHAR(255),v_ano VARCHAR(4),v_mes VARCHAR(2),v_parcela VARCHAR(3),v_id_fin_eventos INT,v_id_cad_servidores INT) RETURNS decimal(11,2)
    COMMENT 'Retorna o valor da rúbrica de um servidor, evento e folha'
BEGIN
	declare r_valor decimal(11,2);
	SELECT fin_rubricas.valor into r_valor FROM fin_rubricas WHERE fin_rubricas.dominio = v_dominio AND 
		fin_rubricas.ano = v_ano AND fin_rubricas.mes = v_mes 
		AND fin_rubricas.parcela = v_parcela AND fin_rubricas.id_fin_eventos = v_id_fin_eventos
		AND fin_rubricas.id_cad_servidores = v_id_cad_servidores;
	if (NOT r_valor > 0) or (r_valor is null) then
		set r_valor = 0;
	end if;
	return r_valor;
    END */$$
DELIMITER ;

/* Function  structure for function  `getIdEvento` */

/*!50003 DROP FUNCTION IF EXISTS `getIdEvento` */;
DELIMITER $$

/*!50003 CREATE FUNCTION `getIdEvento`(v_dominio varchar(255), v_id_evento char(4)) RETURNS int(11)
    COMMENT 'Retorna o id_fin_evento'
BEGIN
	declare r_id_fin_eventos int;
	select id into r_id_fin_eventos from fin_eventos where dominio = v_dominio and id_evento = v_id_evento;
	return r_id_fin_eventos;
    END */$$
DELIMITER ;

/* Function  structure for function  `getIdVinculo` */

/*!50003 DROP FUNCTION IF EXISTS `getIdVinculo` */;
DELIMITER $$

/*!50003 CREATE FUNCTION `getIdVinculo`(v_dominio VARCHAR(255),v_id_vinculo INT) RETURNS int(11)
    DETERMINISTIC
    COMMENT 'Retorna o id_fin_eventos baseado em cad_sfuncional.id_vinculo'
BEGIN
	DECLARE r_id_vinculo INT;
	IF (v_id_vinculo < 4) THEN
		SET r_id_vinculo = (SELECT id FROM fin_eventos WHERE dominio = v_dominio AND id_evento = '001');
	ELSEIF (v_id_vinculo BETWEEN 4 AND 5) THEN
		SET r_id_vinculo = (SELECT id FROM fin_eventos WHERE dominio = v_dominio AND id_evento = '003');
	ELSEIF (v_id_vinculo = 6) THEN 
		SET r_id_vinculo = (SELECT id FROM fin_eventos WHERE dominio = v_dominio AND id_evento = '002');
	ELSE 
		SET r_id_vinculo = 0;
	END IF;
	RETURN r_id_vinculo;
    END */$$
DELIMITER ;

/* Function  structure for function  `getLiquidos` */

/*!50003 DROP FUNCTION IF EXISTS `getLiquidos` */;
DELIMITER $$

/*!50003 CREATE FUNCTION `getLiquidos`(v_dominio VARCHAR(255),v_ano VARCHAR(4),v_mes VARCHAR(2),v_parcela VARCHAR(3),v_id_cad_servidores INT) RETURNS double(11,2)
    COMMENT 'Retorna o líquido de um servidor numa folha'
BEGIN
	DECLARE receitas DOUBLE(11,2);
	DECLARE descontos DOUBLE(11,2);
	SELECT if(SUM(fr.valor) is not null, SUM(fr.valor), 0) INTO receitas 
	FROM fin_rubricas fr 
	JOIN fin_eventos ev ON fr.`id_fin_eventos` = ev.`id` AND ev.`tipo` = 0 
	WHERE fr.`id_cad_servidores` = v_id_cad_servidores AND fr.dominio = v_dominio AND fr.ano = v_ano AND fr.mes = v_mes AND fr.parcela = v_parcela;
	SELECT IF(SUM(fr.valor) IS NOT NULL, SUM(fr.valor), 0) INTO descontos 
	FROM fin_rubricas fr 
	JOIN fin_eventos ev ON fr.`id_fin_eventos` = ev.`id` AND ev.`tipo` = 1 
	WHERE fr.`id_cad_servidores` = v_id_cad_servidores AND fr.dominio = v_dominio AND fr.ano = v_ano AND fr.mes = v_mes AND fr.parcela = v_parcela; 
	RETURN receitas - descontos;
END */$$
DELIMITER ;

/* Function  structure for function  `getMargemConsignavel` */

/*!50003 DROP FUNCTION IF EXISTS `getMargemConsignavel` */;
DELIMITER $$

/*!50003 CREATE FUNCTION `getMargemConsignavel`(v_ano VARCHAR(4),v_mes VARCHAR(2),v_parcela VARCHAR(3), v_id_cad_servidores INT) RETURNS decimal(11,2)
    COMMENT 'Retorna a margem consignável do servidor referente a um determinado período'
BEGIN
	DECLARE r_valor, r_margemC, r_margemD, r_consignadoPos, r_margemFinal FLOAT(11,2);
	DECLARE r_id_evento, r_consignavel, r_deduzconsig, r_consignado INTEGER;
	
	DECLARE curlRubricas CURSOR FOR  
		SELECT fr.valor, fe.id_evento, fe.consignavel, fe.deduzconsig, fe.consignado FROM fin_rubricas fr
		JOIN fin_eventos fe ON fe.id = fr.id_fin_eventos
		WHERE ano = v_ano AND mes = v_mes AND parcela = v_parcela AND id_cad_servidores = v_id_cad_servidores;
	SET r_margemC = 0;
	SET r_margemD = 0;
	SET r_consignadoPos = 0;
	
	OPEN curlRubricas;
	BEGIN
		DECLARE exit_flag INT DEFAULT 0;
		DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET exit_flag = 1;
		rubricasLoop: LOOP
			FETCH curlRubricas INTO r_valor, r_id_evento, r_consignavel, r_deduzconsig, r_consignado;
			IF exit_flag THEN LEAVE rubricasLoop; END IF;
			IF (r_consignavel) THEN
				SET r_margemC = r_margemC + r_valor;
			END IF;
			IF (r_deduzconsig AND !r_consignado) THEN
				SET r_margemC = r_margemC - r_valor;
			END IF;
			IF (r_deduzconsig AND r_consignado) THEN
				SET r_consignadoPos = r_consignadoPos + r_valor;
			END IF;			
		END LOOP;
	END;
	CLOSE curlRubricas;
	IF (r_margemC > 0) THEN
		SET r_margemD = ((r_margemC / 100) * 30) - r_consignadoPos;
		IF (r_margemD > 0) THEN
			SET r_margemFinal = r_margemD;
		END IF;
	END IF;
	RETURN r_margemFinal;
    END */$$
DELIMITER ;

/* Function  structure for function  `getMesExtenso` */

/*!50003 DROP FUNCTION IF EXISTS `getMesExtenso` */;
DELIMITER $$

/*!50003 CREATE FUNCTION `getMesExtenso`(v_mes char(2)) RETURNS char(3) CHARSET utf8mb4
    COMMENT 'Retorna o exteso do mês informado em v_mes'
BEGIN
	declare r_extenso char(3);
	SELECT 
	CASE
	    WHEN CAST(v_mes AS UNSIGNED) = 1 THEN "Jan"
	    WHEN CAST(v_mes AS UNSIGNED) = 2 THEN "Fev"
	    WHEN CAST(v_mes AS UNSIGNED) = 3 THEN "Mar"
	    WHEN CAST(v_mes AS UNSIGNED) = 4 THEN "Abr"
	    WHEN CAST(v_mes AS UNSIGNED) = 5 THEN "Mai"
	    WHEN CAST(v_mes AS UNSIGNED) = 6 THEN "Jun"
	    WHEN CAST(v_mes AS UNSIGNED) = 7 THEN "Jul"
	    WHEN CAST(v_mes AS UNSIGNED) = 8 THEN "Ago"
	    WHEN CAST(v_mes AS UNSIGNED) = 9 THEN "Set"
	    WHEN CAST(v_mes AS UNSIGNED) = 10 THEN "Out"
	    WHEN CAST(v_mes AS UNSIGNED) = 11 THEN "Nov"
	    WHEN CAST(v_mes AS UNSIGNED) = 12 THEN "Dez"
	    WHEN CAST(v_mes AS UNSIGNED) = 13 THEN "13º"
	    ELSE "???" 
	END
	INTO r_extenso;
	return r_extenso;
    END */$$
DELIMITER ;

/* Function  structure for function  `getParametroAtual` */

/*!50003 DROP FUNCTION IF EXISTS `getParametroAtual` */;
DELIMITER $$

/*!50003 CREATE FUNCTION `getParametroAtual`(idUser int) RETURNS int(11)
    COMMENT 'Retorna o parâmetro atual do usuário'
BEGIN
	return (SELECT fp.id FROM fin_parametros fp 
	JOIN mgfolha_folha.user u ON u.per_mes = fp.mes AND u.per_ano = fp.ano AND u.per_parcela = fp.parcela AND u.dominio = fp.dominio
	WHERE u.id = idUser);
    END */$$
DELIMITER ;

/* Function  structure for function  `getSFalecimento` */

/*!50003 DROP FUNCTION IF EXISTS `getSFalecimento` */;
DELIMITER $$

/*!50003 CREATE FUNCTION `getSFalecimento`(v_id_cad_servidores INT) RETURNS int(11)
    COMMENT 'Retorna um model caso o servidor esteja falecido ou null caso não esteja'
BEGIN	
	declare quant int;
	SELECT COUNT(*)
		FROM `cad_smovimentacao` 
		WHERE (`id_cad_servidores`=v_id_cad_servidores) 
		AND ((`codigo_afastamento`='S2') OR (`codigo_afastamento`='S3')) into quant;
	return quant;
    END */$$
DELIMITER ;

/* Function  structure for function  `getUseOfIdEvento` */

/*!50003 DROP FUNCTION IF EXISTS `getUseOfIdEvento` */;
DELIMITER $$

/*!50003 CREATE FUNCTION `getUseOfIdEvento`(v_dominio varchar(255), v_id_fin_eventos char(4)) RETURNS int(11)
    COMMENT 'Retorna o id_fin_evento'
BEGIN
	declare r_quant int;
	select count(id) into r_quant from fin_rubricas where dominio = v_dominio and id_fin_eventos = v_id_fin_eventos;
	return r_quant;
    END */$$
DELIMITER ;

/* Function  structure for function  `getVinculoLabel` */

/*!50003 DROP FUNCTION IF EXISTS `getVinculoLabel` */;
DELIMITER $$

/*!50003 CREATE FUNCTION `getVinculoLabel`(status varchar(3)) RETURNS varchar(255) CHARSET utf8mb4
    COMMENT 'Retorna um label para um vinculo funcional informado'
BEGIN
	declare ret varchar(255);
	SELECT 
	CASE
	    WHEN STATUS = 1 THEN "Efetivo"
	    WHEN STATUS = 2 THEN "Comissionado"
	    WHEN STATUS = 3 THEN "Contratado"
	    WHEN STATUS = 4 THEN "Aposentado"
	    WHEN STATUS = 5 THEN "Pensionista"
	    WHEN STATUS = 6 THEN "Eletivo"
	    WHEN STATUS = 7 THEN "Estagiário"
	    ELSE "Não informado"
	END
	into ret
	FROM orgao limit 1;
	return ret;
    END */$$
DELIMITER ;

/* Procedure structure for procedure `fin_folha_descontos` */

/*!50003 DROP PROCEDURE IF EXISTS  `fin_folha_descontos` */;

DELIMITER $$

/*!50003 CREATE PROCEDURE `fin_folha_descontos`(v_id_user VARCHAR(255),v_dominio VARCHAR(255),v_ano VARCHAR(4),v_mes VARCHAR(2),
v_parcela VARCHAR(3),v_id_cad_servidor VARCHAR(255))
    COMMENT 'Procedimento visa '
BEGIN
END */$$
DELIMITER ;

/* Procedure structure for procedure `fin_folha_eventos` */

/*!50003 DROP PROCEDURE IF EXISTS  `fin_folha_eventos` */;

DELIMITER $$

/*!50003 CREATE PROCEDURE `fin_folha_eventos`(v_id_user INT(11), v_dominio VARCHAR(255),v_ano VARCHAR(4),v_mes VARCHAR(2),v_parcela VARCHAR(3), v_id_cad_servidores INT)
BEGIN 
DECLARE done INT DEFAULT FALSE; 
DECLARE v_id_cad_servidor INT; 
DECLARE cur1 CURSOR FOR 
SELECT fin_sfuncional.id_cad_servidores 
	FROM fin_sfuncional 
	JOIN cad_servidores ON cad_servidores.id = fin_sfuncional.id_cad_servidores 
	WHERE fin_sfuncional.dominio = v_dominio AND fin_sfuncional.ano = v_ano 
	AND fin_sfuncional.mes = v_mes AND fin_sfuncional.parcela = v_parcela AND fin_sfuncional.situacao = 1 
	AND fin_sfuncional.situacaofuncional >= 1 AND (fin_sfuncional.id_pccs IS NOT NULL AND fin_sfuncional.id_pccs > 0) 
	AND fin_sfuncional.lanca_salario = 1
	AND IF (v_id_cad_servidores > 0, fin_sfuncional.id_cad_servidores = v_id_cad_servidores, 1=1)
	ORDER BY cad_servidores.id; 
DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE; 
 
OPEN cur1; 
read_loop: LOOP FETCH cur1 INTO v_id_cad_servidor; 
	IF done THEN 
		LEAVE read_loop; 
	END IF; 
	
	CALL fin_folha_eventos_basico(v_id_user,v_dominio,v_ano,v_mes,v_parcela,v_id_cad_servidor);
END LOOP; 
CLOSE cur1; 
END */$$
DELIMITER ;

/* Procedure structure for procedure `fin_folha_eventos_basico` */

/*!50003 DROP PROCEDURE IF EXISTS  `fin_folha_eventos_basico` */;

DELIMITER $$

/*!50003 CREATE PROCEDURE `fin_folha_eventos_basico`(v_id_user VARCHAR(255),v_dominio VARCHAR(255),v_ano VARCHAR(4),v_mes VARCHAR(2),
v_parcela VARCHAR(3),v_id_cad_servidor VARCHAR(255))
BEGIN
 DECLARE r_valor, v_valor DECIMAL(11,2);
 DECLARE v_referencia, v_anos, v_meses, v_dias CHAR(2);
 DECLARE v_i_ano_inicial, v_i_ano_final CHAR(3);
 DECLARE v_id_pccs, v_id_vinculo, v_dias_mes, v_dias_trabalhados, r_id_evento, r_id_fin_eventos, r_id_fin_rubricas INT;
 DECLARE v_d_admissao DATE; 
 DECLARE done INT DEFAULT FALSE; 
 DECLARE exec INT DEFAULT TRUE;  
 
 DECLARE cur1Referencias CURSOR FOR  
	SELECT fin_referencias.valor, cad_classes.i_ano_inicial, cad_classes.i_ano_final 
		FROM fin_referencias 
		JOIN cad_pccs ON cad_pccs.dominio = fin_referencias.dominio 
			AND cad_pccs.id_pccs = fin_referencias.id_pccs 
		JOIN cad_classes ON cad_classes.dominio = fin_referencias.dominio 
			AND cad_classes.id_pccs = cad_pccs.id_pccs 
			AND cad_classes.id_classe = fin_referencias.id_classe 
		WHERE (cad_pccs.dominio=v_dominio) AND (fin_referencias.id_pccs=v_id_pccs) 
			AND (STR_TO_DATE(fin_referencias.data, '%d/%m/%Y') <= LAST_DAY(CONCAT(v_ano, '-', v_mes, '-1'))) 
			AND CAST(v_anos AS UNSIGNED) BETWEEN CAST(cad_classes.i_ano_inicial AS UNSIGNED) AND CAST(cad_classes.i_ano_final AS UNSIGNED)
		ORDER BY fin_referencias.id_classe DESC, fin_referencias.referencia DESC
		LIMIT 100000;
 DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
 
 SET v_dias_mes = DATE_FORMAT(LAST_DAY(CONCAT(v_ano, '-', v_mes, '-1')), '%d');
 SET v_dias_trabalhados = getDiasPagarVctoBasico(v_id_cad_servidor,v_ano,v_mes);
 SET v_referencia = v_dias_trabalhados;
 SET r_valor = 0;
 
 SELECT fin_sfuncional.id_pccs,cad_sfuncional.id_vinculo,
	
	STR_TO_DATE(cad_servidores.d_admissao, '%d/%m/%Y')data_adm,
	TIMESTAMPDIFF(YEAR, STR_TO_DATE(cad_servidores.d_admissao, '%d/%m/%Y'), LAST_DAY(CONCAT(fin_sfuncional.ano, '-', fin_sfuncional.mes,'-1'))) AS anos,
	TIMESTAMPDIFF(MONTH, STR_TO_DATE(cad_servidores.d_admissao, '%d/%m/%Y') + INTERVAL TIMESTAMPDIFF(YEAR,  STR_TO_DATE(cad_servidores.d_admissao, '%d/%m/%Y'), LAST_DAY(CONCAT(fin_sfuncional.ano, '-', fin_sfuncional.mes,'-1'))) YEAR , LAST_DAY(CONCAT(fin_sfuncional.ano, '-', fin_sfuncional.mes,'-1'))) AS meses,
	TIMESTAMPDIFF(DAY, STR_TO_DATE(cad_servidores.d_admissao, '%d/%m/%Y') + INTERVAL TIMESTAMPDIFF(MONTH,  STR_TO_DATE(cad_servidores.d_admissao, '%d/%m/%Y'), LAST_DAY(CONCAT(fin_sfuncional.ano, '-', fin_sfuncional.mes,'-1'))) MONTH , LAST_DAY(CONCAT(fin_sfuncional.ano, '-', fin_sfuncional.mes,'-1'))) + 1 AS dias
	INTO v_id_pccs, v_id_vinculo, v_d_admissao, v_anos, v_meses, v_dias
	FROM cad_sfuncional
	JOIN cad_servidores ON cad_sfuncional.id_cad_servidores = cad_servidores.id
	JOIN fin_sfuncional ON cad_sfuncional.id_cad_servidores = fin_sfuncional.id_cad_servidores AND 
	CAST(cad_sfuncional.parcela as unsigned CAST(fin_sfuncional.ano parcela as unsignedST(cad_sfuncional.mes as unsigned) <= CAST(fin_sfuncional.mes as unsigned) AND
	CAST(cad_sfuncional.parcela as unsigned) <= CAST(fin_sfuncional.parcela as unsigned)
	WHERE fin_sfuncional.ano = v_ano AND fin_sfuncional.mes = v_mes AND fin_sfuncional.parcela = v_parcela 
	AND fin_sfuncional.id_cad_servidores = v_id_cad_servidor
	GROUP BY fin_sfuncional.id_pccs,cad_sfuncional.id_vinculo,cad_servidores.d_admissao,fin_sfuncional.ano,fin_sfuncional.mes;
	
	OPEN cur1Referencias;
	read_loop: LOOP
		FETCH cur1Referencias INTO v_valor, v_i_ano_inicial, v_i_ano_final;
		IF done THEN
			LEAVE read_loop;
		END IF;
		IF !(v_anos = v_i_ano_final && (v_meses != 0 || v_dias != 0)) THEN
			SET r_valor = ((v_valor / v_dias_mes) * v_dias_trabalhados);
			IF v_i_ano_final != 0 AND v_i_ano_inicial != 99 THEN
				SET done = TRUE;
			ELSE 
				SET done = FALSE;
			END IF;
		END IF;
	END LOOP;
	CLOSE cur1Referencias;
	
 SET r_id_fin_eventos = getidvinculo(v_dominio,v_id_vinculo);
 
 IF v_dias_trabalhados > 0 THEN
	SET exec = TRUE;
 ELSE
	SET exec = FALSE;
 END IF;
 
IF ((exec = TRUE) and (v_id_cad_servidor > 0)) THEN
	
	SET r_id_fin_rubricas = (SELECT MAX(id) FROM fin_rubricas) + 1;
		
	IF (SELECT id FROM fin_rubricas WHERE id = r_id_fin_rubricas) IS NOT NULL THEN
		SET r_id_fin_rubricas = r_id_fin_rubricas + 1;
	END IF;	
	SET r_id_evento = getEventoSistema(v_dominio,CONCAT('Inclusão da rúbrica id: ', r_id_fin_eventos),'actionC', 'fin_rubricas', r_id_fin_rubricas, v_id_user);
	
	INSERT INTO fin_rubricas (id,slug,STATUS,dominio,evento,created_at,updated_at,
		id_cad_servidores,id_fin_eventos,
		ano,mes,parcela,referencia,valor_base,valor,valor_patronal,valor_maternidade,prazo,prazot,valor_desconto,valor_percentual) 
	VALUES(
		r_id_fin_rubricas,SHA(r_id_fin_rubricas),10,v_dominio,r_id_evento,UNIX_TIMESTAMP(NOW()),UNIX_TIMESTAMP(NOW()),
		v_id_cad_servidor,r_id_fin_eventos,
		v_ano,v_mes,v_parcela,v_referencia,0.00,r_valor,0.00,0.00,1,1,0.00,100.00
	);
 END IF; 
 
 
END */$$
DELIMITER ;

/* Procedure structure for procedure `fin_folha_eventos_consignados` */

/*!50003 DROP PROCEDURE IF EXISTS  `fin_folha_eventos_consignados` */;

DELIMITER $$

/*!50003 CREATE PROCEDURE `fin_folha_eventos_consignados`(v_id_user INT(11),v_dominio VARCHAR(255),v_ano VARCHAR(4),v_mes VARCHAR(2),v_parcela VARCHAR(3), v_id_cad_servidores INT)
BEGIN
	DECLARE done INT DEFAULT FALSE;
	DECLARE r_id_fin_rubricas,r_id_cad_servidores,r_id_fin_eventos,r_prazo,r_prazot,r_id_evento INT(11);
	DECLARE r_slug,r_referencia,r_valor_base,r_valor,r_valor_patronal,
			r_valor_maternidade,r_desconto,r_percentual VARCHAR(255);	 
	
	DECLARE cur1Referencias CURSOR FOR  
		SELECT fin_rubricas.id_cad_servidores,fin_rubricas.id_fin_eventos,
			fin_rubricas.referencia,fin_rubricas.valor_base,fin_rubricas.valor,fin_rubricas.valor_patronal,
			fin_rubricas.valor_maternidade,fin_rubricas.valor_desconto,fin_rubricas.valor_percentual,
			fin_rubricas.prazo + 1,fin_rubricas.prazot 
			FROM fin_rubricas
			
			JOIN fin_parametros ON fin_parametros.ano_informacao = fin_rubricas.ano
			AND fin_parametros.mes_informacao = fin_rubricas.mes AND fin_parametros.parcela_informacao = fin_rubricas.parcela
			AND fin_parametros.dominio = fin_rubricas.dominio
			JOIN fin_eventos ON fin_eventos.id = fin_rubricas.id_fin_eventos
			JOIN fin_sfuncional ON fin_parametros.ano_informacao = fin_sfuncional.ano
			AND fin_parametros.mes_informacao = fin_sfuncional.mes AND fin_parametros.parcela_informacao = fin_sfuncional.parcela
			AND fin_rubricas.dominio = fin_sfuncional.dominio and fin_sfuncional.id_cad_servidores = fin_rubricas.id_cad_servidores
			WHERE fin_rubricas.dominio = v_dominio AND fin_parametros.ano = v_ano 
			AND fin_parametros.mes = v_mes AND fin_parametros.parcela = v_parcela
			AND fin_rubricas.prazot > fin_rubricas.prazo AND fin_eventos.consignado = 1 AND fin_rubricas.valor > 0
			AND fin_sfuncional.situacao = 1 AND fin_sfuncional.situacaofuncional >= 1
			AND IF (v_id_cad_servidores > 0, fin_sfuncional.id_cad_servidores = v_id_cad_servidores, 1=1)
			GROUP BY fin_rubricas.id_cad_servidores,fin_rubricas.id_fin_eventos,fin_parametros.ano,fin_parametros.mes,fin_parametros.parcela
			ORDER BY fin_rubricas.id_cad_servidores,fin_eventos.tipo,fin_eventos.id_evento;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
	
	SET r_id_fin_rubricas = (SELECT MAX(id) FROM fin_rubricas);	
	
	
	OPEN cur1Referencias;
	read_loop: LOOP
		FETCH cur1Referencias INTO r_id_cad_servidores,r_id_fin_eventos,r_referencia,
			r_valor_base,r_valor,r_valor_patronal,r_valor_maternidade,r_desconto,
			r_percentual,r_prazo,r_prazot;
		IF done THEN
			LEAVE read_loop;
		END IF;	
		
		if (SELECT getSFalecimento(r_id_cad_servidores) = 0) then
			
			SET r_slug = SHA(CONCAT(UNIX_TIMESTAMP(NOW()),r_id_fin_rubricas));
			
			SET r_id_fin_rubricas = r_id_fin_rubricas + 1;	
				
			IF (SELECT id FROM fin_rubricas WHERE id = r_id_fin_rubricas) IS NOT NULL THEN
				SET r_id_fin_rubricas = r_id_fin_rubricas + 1;
			END IF;
			SET r_id_evento = getEventoSistema(v_dominio,CONCAT('Inclusão da rúbrica parcelada id: ', r_id_fin_eventos),'actionC', 'fin_rubricas', r_id_fin_rubricas, v_id_user);
			IF (v_id_cad_servidores > 0) THEN		
				DELETE FROM fin_rubricas WHERE dominio = v_dominio 
					AND ano = v_ano AND mes = v_mes AND parcela = v_parcela 
					AND id_fin_eventos = r_id_fin_eventos
					AND id_cad_servidores = r_id_cad_servidores; 
			END IF;
			INSERT INTO fin_rubricas(
				id,slug,status,dominio,evento,created_at,updated_at,
				id_cad_servidores,id_fin_eventos,ano,mes,parcela,
				referencia,valor_base,valor,valor_patronal,
				valor_maternidade,prazo,prazot,valor_desconto,valor_percentual
			) VALUES (
				r_id_fin_rubricas,r_slug,10,v_dominio,r_id_evento,UNIX_TIMESTAMP(NOW()),UNIX_TIMESTAMP(NOW()),
				r_id_cad_servidores,r_id_fin_eventos,v_ano,v_mes,v_parcela,
				r_referencia,r_valor_base,r_valor,r_valor_patronal,
				r_valor_maternidade,r_prazo,r_prazot,r_desconto,r_percentual
			);
		end if;
	END LOOP;
	CLOSE cur1Referencias;
END */$$
DELIMITER ;

/* Procedure structure for procedure `fin_folha_eventos_enios` */

/*!50003 DROP PROCEDURE IF EXISTS  `fin_folha_eventos_enios` */;

DELIMITER $$

/*!50003 CREATE PROCEDURE `fin_folha_eventos_enios`(v_id_user INT(11),v_dominio VARCHAR(255),v_ano VARCHAR(4),
	v_mes VARCHAR(2),v_parcela VARCHAR(3), v_id_cad_servidores int)
    COMMENT 'Lança os ênios dos servidores'
BEGIN
	DECLARE r_id_fin_rubricas,r_id_cad_servidores,r_id_fin_eventos,r_prazo,r_prazot,
		r_anos, r_meses, r_dias, r_enio, r_enios, r_id_evento, contator INT;
	DECLARE r_slug,r_referencia VARCHAR(255);	
	DECLARE r_valor_base,r_valor,r_valor_patronal,
			r_valor_maternidade,r_desconto,r_percentual,
			r_base_percentual decimal(11,2);
	
	DECLARE curlServidores CURSOR FOR  
		SELECT fin_sfuncional.id_cad_servidores FROM fin_sfuncional
			WHERE fin_sfuncional.enio > 0 AND fin_sfuncional.dominio = v_dominio AND fin_sfuncional.ano = v_ano 
			AND fin_sfuncional.mes = v_mes AND fin_sfuncional.parcela = v_parcela
			AND fin_sfuncional.situacao = 1 AND fin_sfuncional.situacaofuncional >= 1 
			AND if (v_id_cad_servidores > 0, fin_sfuncional.id_cad_servidores = v_id_cad_servidores, 1=1)
			GROUP BY fin_sfuncional.id_cad_servidores
			ORDER BY fin_sfuncional.id_cad_servidores;
	
	DECLARE curlBase CURSOR FOR
		/* Esta query é a mesma que `get_eventos_base`*/
		select fin_eventosbase.id_fin_eventosbase FROM fin_eventosbase 
			JOIN fin_eventos a ON a.id = fin_eventosbase.id_fin_eventos 
			JOIN fin_eventos b ON b.id = fin_eventosbase.id_fin_eventosbase 
			WHERE (fin_eventosbase.dominio=v_dominio) AND (fin_eventosbase.id_fin_eventos=r_id_fin_eventos)
			order by cast(a.id_evento as unsigned);
		/* Esta era a query até 14/05/2020 SELECT fin_eventosbase.id_fin_eventosbase
			FROM fin_eventosbase 
			WHERE fin_eventosbase.id_fin_eventos = r_id_fin_eventos  AND fin_eventosbase.dominio = v_dominio 
			ORDER BY fin_eventosbase.id_fin_eventosbase;*/	
	
	set contator = 1;
	
	SET r_id_fin_rubricas = (SELECT MAX(id) FROM fin_rubricas);
	
	OPEN curlServidores;
	BEGIN
		DECLARE exit_flag INT DEFAULT 0;
		DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET exit_flag = 1;
		servidoresLoop: LOOP
			FETCH curlServidores INTO r_id_cad_servidores;
			IF exit_flag THEN LEAVE servidoresLoop; END IF;
			
			SET r_slug = SHA(CONCAT(UNIX_TIMESTAMP(NOW()),contator, r_id_cad_servidores));
			set contator = contator + 1;
			
			CALL get_tempo_carteira(v_dominio,v_ano,v_mes,v_parcela,r_id_cad_servidores, 
				r_anos, r_meses, r_dias, r_enio, r_enios, r_id_fin_eventos);
			OPEN curlBase;
			BEGIN
				DECLARE exit_flag_base INT DEFAULT 0;	
				DECLARE id_fin_eventobase INT DEFAULT 0; 
				DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET exit_flag_base = 1;
				SET r_valor = 0;
				baseLoop: LOOP
					FETCH curlBase INTO id_fin_eventobase;
					IF exit_flag_base THEN LEAVE baseLoop; END IF;
					
					SET r_valor = r_valor + (getFinRubricasEvtValor(v_dominio,v_ano,v_mes,v_parcela,id_fin_eventobase,r_id_cad_servidores) * (r_enio * 0.01) * r_enios);
				END LOOP;
			end;
			CLOSE curlBase;			
			set r_referencia = lpad(r_enios, 2, '0');
				
			IF (SELECT id FROM fin_rubricas WHERE id = r_id_fin_rubricas) IS NOT NULL THEN
				SET r_id_fin_rubricas = r_id_fin_rubricas + 1;
			END IF;
			
			SET r_id_evento = getEventoSistema(v_dominio,CONCAT('Inclusão da rúbrica de enio id: ', r_id_fin_eventos),'actionC', 'fin_rubricas', r_id_fin_rubricas, v_id_user);
			SET r_valor_base = 0, r_valor_patronal = 0, r_valor_maternidade = 0, r_desconto = 0, r_percentual = 0, r_prazo = 1, r_prazot = 1;			
			IF (v_id_cad_servidores > 0) THEN		
				DELETE FROM fin_rubricas WHERE (dominio = v_dominio 
					AND ano = v_ano AND mes = v_mes AND parcela = v_parcela 
					AND id_fin_eventos = r_id_fin_eventos
					AND id_cad_servidores = r_id_cad_servidores)
					or id_fin_eventos is null; 
			END IF;
			INSERT INTO fin_rubricas(
				id,slug,STATUS,dominio,evento,created_at,updated_at,
				id_cad_servidores,id_fin_eventos,ano,mes,parcela,
				referencia,valor_base,valor,valor_patronal,
				valor_maternidade,prazo,prazot,valor_desconto,valor_percentual
			) VALUES (
				r_id_fin_rubricas,r_slug,10,v_dominio,r_id_evento,UNIX_TIMESTAMP(NOW()),UNIX_TIMESTAMP(NOW()),
				r_id_cad_servidores,r_id_fin_eventos,v_ano,v_mes,v_parcela,
				r_referencia,r_valor_base,r_valor,r_valor_patronal,
				r_valor_maternidade,r_prazo,r_prazot,r_desconto,r_percentual
			);
			
		END LOOP;
	end;
	CLOSE curlServidores;
    END */$$
DELIMITER ;

/* Procedure structure for procedure `fin_folha_eventos_parcelados` */

/*!50003 DROP PROCEDURE IF EXISTS  `fin_folha_eventos_parcelados` */;

DELIMITER $$

/*!50003 CREATE PROCEDURE `fin_folha_eventos_parcelados`(v_id_user INT(11),v_dominio VARCHAR(255),v_ano VARCHAR(4),v_mes VARCHAR(2),v_parcela VARCHAR(3), v_id_cad_servidores INT)
BEGIN
	DECLARE done INT DEFAULT FALSE;
	DECLARE r_id_fin_rubricas,r_id_cad_servidores,r_id_fin_eventos,r_prazo,r_prazot,r_id_evento INT(11);
	DECLARE r_slug,r_referencia,r_valor_base,r_valor,r_valor_patronal,
			r_valor_maternidade,r_desconto,r_percentual VARCHAR(255);	 
	
	DECLARE cur1Referencias CURSOR FOR  
		SELECT fin_rubricas.id_cad_servidores,fin_rubricas.id_fin_eventos,
			fin_rubricas.referencia,fin_rubricas.valor_base,fin_rubricas.valor,fin_rubricas.valor_patronal,
			fin_rubricas.valor_maternidade,fin_rubricas.valor_desconto,fin_rubricas.valor_percentual,
			fin_rubricas.prazo + 1,fin_rubricas.prazot 
			FROM fin_rubricas
			
			JOIN fin_parametros ON fin_parametros.ano_informacao = fin_rubricas.ano
			AND fin_parametros.mes_informacao = fin_rubricas.mes AND fin_parametros.parcela_informacao = fin_rubricas.parcela
			AND fin_parametros.dominio = fin_rubricas.dominio
			JOIN fin_eventos ON fin_eventos.id = fin_rubricas.id_fin_eventos
			JOIN fin_sfuncional ON fin_parametros.ano_informacao = fin_sfuncional.ano
			AND fin_parametros.mes_informacao = fin_sfuncional.mes AND fin_parametros.parcela_informacao = fin_sfuncional.parcela
			AND fin_rubricas.dominio = fin_sfuncional.dominio AND fin_sfuncional.id_cad_servidores = fin_rubricas.id_cad_servidores
			WHERE fin_rubricas.dominio = v_dominio AND fin_parametros.ano = v_ano 
			AND fin_parametros.mes = v_mes AND fin_parametros.parcela = v_parcela
			AND fin_rubricas.prazot > fin_rubricas.prazo AND fin_eventos.consignado = 0 AND fin_rubricas.valor > 0
			AND fin_sfuncional.situacao = 1 AND fin_sfuncional.situacaofuncional >= 1
			AND if (v_id_cad_servidores > 0, fin_sfuncional.id_cad_servidores = v_id_cad_servidores, 1=1)
			GROUP BY fin_rubricas.id_cad_servidores,fin_rubricas.id_fin_eventos,fin_parametros.ano,fin_parametros.mes,fin_parametros.parcela
			ORDER BY fin_rubricas.id_cad_servidores,fin_eventos.tipo,fin_eventos.id_evento;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
	
	SET r_id_fin_rubricas = (SELECT MAX(id) FROM fin_rubricas);	
	
	
	OPEN cur1Referencias;
	read_loop: LOOP
		FETCH cur1Referencias INTO r_id_cad_servidores,r_id_fin_eventos,r_referencia,
			r_valor_base,r_valor,r_valor_patronal,r_valor_maternidade,r_desconto,
			r_percentual,r_prazo,r_prazot;
		IF done THEN
			LEAVE read_loop;
		END IF;	
		
		SET r_slug = SHA(CONCAT(UNIX_TIMESTAMP(NOW()),r_id_fin_rubricas));
		
		SET r_id_fin_rubricas = r_id_fin_rubricas + 1;	
			
		IF (SELECT id FROM fin_rubricas WHERE id = r_id_fin_rubricas) IS NOT NULL THEN
			SET r_id_fin_rubricas = r_id_fin_rubricas + 1;
		END IF;
		SET r_id_evento = getEventoSistema(v_dominio,CONCAT('Inclusão da rúbrica parcelada id: ', r_id_fin_eventos),'actionC', 'fin_rubricas', r_id_fin_rubricas, v_id_user);
		IF (v_id_cad_servidores > 0) THEN		
			DELETE FROM fin_rubricas WHERE dominio = v_dominio 
				AND ano = v_ano AND mes = v_mes AND parcela = v_parcela 
				AND id_fin_eventos = r_id_fin_eventos
				AND id_cad_servidores = r_id_cad_servidores; 
		END IF;
		INSERT INTO fin_rubricas(
			id,slug,`status`,dominio,evento,created_at,updated_at,
			id_cad_servidores,id_fin_eventos,ano,mes,parcela,
			referencia,valor_base,valor,valor_patronal,
			valor_maternidade,prazo,prazot,valor_desconto,valor_percentual
		) VALUES (
			r_id_fin_rubricas,r_slug,10,v_dominio,r_id_evento,UNIX_TIMESTAMP(NOW()),UNIX_TIMESTAMP(NOW()),
			r_id_cad_servidores,r_id_fin_eventos,v_ano,v_mes,v_parcela,
			r_referencia,r_valor_base,r_valor,r_valor_patronal,
			r_valor_maternidade,r_prazo,r_prazot,r_desconto,r_percentual
		);
	END LOOP;
	CLOSE cur1Referencias;
END */$$
DELIMITER ;

/* Procedure structure for procedure `fin_folha_eventos_recorrentes` */

/*!50003 DROP PROCEDURE IF EXISTS  `fin_folha_eventos_recorrentes` */;

DELIMITER $$

/*!50003 CREATE PROCEDURE `fin_folha_eventos_recorrentes`(v_id_user INT(11),v_dominio VARCHAR(255),v_ano VARCHAR(4),v_mes VARCHAR(2),v_parcela VARCHAR(3), v_id_cad_servidores INT)
BEGIN
	DECLARE done INT DEFAULT FALSE;
	DECLARE r_id_fin_rubricas,r_id_cad_servidores,r_id_fin_eventos,r_prazo,r_prazot,r_id_evento INT(11);
	DECLARE r_slug,r_referencia,r_valor_base,r_valor,r_valor_patronal,
			r_valor_maternidade,r_desconto,r_percentual VARCHAR(255);	 
	
	DECLARE cur1Referencias CURSOR FOR  
		SELECT fin_rubricas.id_cad_servidores,fin_rubricas.id_fin_eventos,
			fin_rubricas.referencia,fin_rubricas.valor_base,fin_rubricas.valor,fin_rubricas.valor_patronal,
			fin_rubricas.valor_maternidade,fin_rubricas.valor_desconto,fin_rubricas.valor_percentual 
			FROM fin_rubricas
			
			JOIN fin_parametros ON fin_parametros.ano_informacao = fin_rubricas.ano
			AND fin_parametros.mes_informacao = fin_rubricas.mes AND fin_parametros.parcela_informacao = fin_rubricas.parcela
			AND fin_parametros.dominio = fin_rubricas.dominio
			JOIN fin_eventos ON fin_eventos.id = fin_rubricas.id_fin_eventos
			JOIN fin_sfuncional ON fin_parametros.ano_informacao = fin_sfuncional.ano
			AND fin_parametros.mes_informacao = fin_sfuncional.mes AND fin_parametros.parcela_informacao = fin_sfuncional.parcela
			AND fin_rubricas.dominio = fin_sfuncional.dominio AND fin_sfuncional.id_cad_servidores = fin_rubricas.id_cad_servidores
			WHERE fin_rubricas.dominio = v_dominio AND fin_parametros.ano = v_ano 
			AND fin_parametros.mes = v_mes AND fin_parametros.parcela = v_parcela			
			AND fin_eventos.ev_root != 1 AND fin_eventos.fixo = 1 AND fin_rubricas.prazot = fin_rubricas.prazo 
			AND fin_rubricas.prazot IN (1,999) and fin_rubricas.valor > 0 
			AND fin_sfuncional.situacao = 1 AND fin_sfuncional.situacaofuncional >= 1
			AND IF (v_id_cad_servidores > 0, fin_sfuncional.id_cad_servidores = v_id_cad_servidores, 1=1)
			GROUP BY fin_rubricas.id_cad_servidores,fin_rubricas.id_fin_eventos,fin_parametros.ano,fin_parametros.mes,fin_parametros.parcela
			ORDER BY fin_rubricas.id_cad_servidores,fin_eventos.tipo,fin_eventos.id_evento;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
	
	SET r_id_fin_rubricas = (SELECT MAX(id) FROM fin_rubricas);	
	
	
	OPEN cur1Referencias;
	read_loop: LOOP
		FETCH cur1Referencias INTO r_id_cad_servidores,r_id_fin_eventos,r_referencia,
			r_valor_base,r_valor,r_valor_patronal,r_valor_maternidade,r_desconto,r_percentual;
		IF done THEN
			LEAVE read_loop;
		END IF;	
		
		SET r_slug = SHA(CONCAT(UNIX_TIMESTAMP(NOW()),r_id_fin_rubricas));
		
		SET r_id_fin_rubricas = r_id_fin_rubricas + 1;	
			
		IF (SELECT id FROM fin_rubricas WHERE id = r_id_fin_rubricas) IS NOT NULL THEN
			SET r_id_fin_rubricas = r_id_fin_rubricas + 1;
		END IF;
		SET r_id_evento = getEventoSistema(v_dominio,CONCAT('Inclusão da rúbrica recorrente id: ', r_id_fin_eventos),'actionC', 'fin_rubricas', r_id_fin_rubricas, v_id_user);
		SET r_valor_base = 0, r_valor = 0, r_valor_patronal = 0, r_valor_maternidade = 0, r_desconto = 0, r_prazo = 1, r_prazot = 1;
		IF (v_id_cad_servidores > 0) THEN		
			DELETE FROM fin_rubricas WHERE dominio = v_dominio 
				AND ano = v_ano AND mes = v_mes AND parcela = v_parcela 
				and id_fin_eventos = r_id_fin_eventos
				and id_cad_servidores = r_id_cad_servidores; 
		END IF;
		INSERT INTO fin_rubricas(
			id,slug,`status`,dominio,evento,created_at,updated_at,
			id_cad_servidores,id_fin_eventos,ano,mes,parcela,
			referencia,valor_base,valor,valor_patronal,
			valor_maternidade,prazo,prazot,valor_desconto,valor_percentual
		) VALUES (
			r_id_fin_rubricas,r_slug,10,v_dominio,r_id_evento,UNIX_TIMESTAMP(NOW()),UNIX_TIMESTAMP(NOW()),
			r_id_cad_servidores,r_id_fin_eventos,v_ano,v_mes,v_parcela,
			r_referencia,r_valor_base,r_valor,r_valor_patronal,
			r_valor_maternidade,r_prazo,r_prazot,r_desconto,r_percentual
		);
	END LOOP;
	CLOSE cur1Referencias;
END */$$
DELIMITER ;

/* Procedure structure for procedure `get_eventos_base` */

/*!50003 DROP PROCEDURE IF EXISTS  `get_eventos_base` */;

DELIMITER $$

/*!50003 CREATE PROCEDURE `get_eventos_base`(v_dominio varchar(255), v_id_fin_evento int)
    COMMENT 'Retorna a base de calculo de um evento'
BEGIN
	select fin_eventosbase.id_fin_eventosbase FROM fin_eventosbase 
		JOIN fin_eventos a ON a.id = fin_eventosbase.id_fin_eventos 
		JOIN fin_eventos b ON b.id = fin_eventosbase.id_fin_eventosbase 
		WHERE (fin_eventosbase.dominio=v_dominio) AND (fin_eventosbase.id_fin_eventos=v_id_fin_evento)
		order by cast(a.id_evento as unsigned);
    END */$$
DELIMITER ;

/* Procedure structure for procedure `get_eventos_desconto` */

/*!50003 DROP PROCEDURE IF EXISTS  `get_eventos_desconto` */;

DELIMITER $$

/*!50003 CREATE PROCEDURE `get_eventos_desconto`(v_dominio varchar(255), v_id_fin_evento int)
    COMMENT 'Retorna a base de calculo de um evento'
BEGIN
	select fin_eventosdesconto.id_fin_eventosdesconto FROM fin_eventosdesconto 
		join fin_eventos a ON a.id = fin_eventosdesconto.id_fin_eventos 
		join fin_eventos b ON b.id = fin_eventosdesconto.id_fin_eventosdesconto 
		WHERE (fin_eventosdesconto.dominio=v_dominio) AND (fin_eventosdesconto.id_fin_eventos=v_id_fin_evento)
		order by cast(a.id_evento as unsigned);
    END */$$
DELIMITER ;

/* Procedure structure for procedure `get_eventos_percentual` */

/*!50003 DROP PROCEDURE IF EXISTS  `get_eventos_percentual` */;

DELIMITER $$

/*!50003 CREATE PROCEDURE `get_eventos_percentual`(v_dominio VARCHAR(255), v_id_fin_evento INT)
    COMMENT 'Retorna a base de descontos de um evento'
BEGIN
	SELECT fin_eventospercentual.id_eventos_percentual FROM fin_eventospercentual 
		join fin_eventos a ON a.id = fin_eventospercentual.id_fin_eventos 
		WHERE (fin_eventospercentual.dominio=v_dominio) AND (fin_eventospercentual.id_fin_eventos=v_id_fin_evento)
		ORDER BY CAST(a.id_evento AS UNSIGNED);
    END */$$
DELIMITER ;

/* Procedure structure for procedure `get_ficha_financeiraA_cacimbinhas` */

/*!50003 DROP PROCEDURE IF EXISTS  `get_ficha_financeiraA_cacimbinhas` */;

DELIMITER $$

/*!50003 CREATE PROCEDURE `get_ficha_financeiraA_cacimbinhas`(v_dominio VARCHAR(255),v_ano VARCHAR(4),v_mes VARCHAR(2),
	v_parcela VARCHAR(3),v_id_cad_servidor VARCHAR(255))
BEGIN
	declare r_base, r_bruto, v_descontos, r_liquido, r_inss, r_rpps, r_irpf decimal(11,2);
	
	-- salário base
	SELECT SUM(base.valor)base into r_base FROM fin_rubricas AS base
	JOIN fin_eventos fe ON fe.id = base.id_fin_eventos
	WHERE fe.tipo = 0 AND fe.evento_nome LIKE '%vencimento%' AND base.`id_cad_servidores` = v_id_cad_servidor AND base.ano = CAST(v_ano as unsigned) AND 
	base.mes = CAST(v_mes as unsigned) AND base.parcela = CAST(v_parcela as unsigned);
	
	-- salário bruto
	SELECT SUM(base.valor)base INTO r_bruto FROM fin_rubricas AS base
	JOIN fin_eventos fe ON fe.id = base.id_fin_eventos
	WHERE fe.tipo = 0 AND base.`id_cad_servidores` = v_id_cad_servidor AND base.ano = CAST(v_ano as unsigned) AND 
	base.mes = CAST(v_mes as unsigned) AND base.parcela = CAST(v_parcela as unsigned);
	
	-- descontos
	SELECT SUM(base.valor)base INTO v_descontos FROM fin_rubricas AS base
	JOIN fin_eventos fe ON fe.id = base.id_fin_eventos
	WHERE fe.tipo = 1 AND base.`id_cad_servidores` = v_id_cad_servidor AND base.ano = CAST(v_ano as unsigned) AND 
	base.mes = CAST(v_mes as unsigned) AND base.parcela = CAST(v_parcela as unsigned);
	set r_liquido = r_bruto - v_descontos;
	
	-- INSS
	SELECT SUM(base.valor)base INTO r_inss FROM fin_rubricas AS base
	JOIN fin_eventos fe ON fe.id = base.id_fin_eventos
	WHERE fe.tipo = 1 AND fe.evento_nome LIKE '%inss%' AND fe.tipo = 1 AND base.`id_cad_servidores` = v_id_cad_servidor AND 
	base.ano = CAST(v_ano as unsigned) AND base.mes = CAST(v_mes as unsigned) AND base.parcela = CAST(v_parcela as unsigned);
	
	-- RPPS
	SELECT SUM(base.valor)base INTO r_rpps FROM fin_rubricas AS base
	JOIN fin_eventos fe ON fe.id = base.id_fin_eventos
	WHERE fe.tipo = 1 AND fe.evento_nome LIKE '%imprec%' AND fe.tipo = 1 AND base.`id_cad_servidores` = v_id_cad_servidor AND 
	base.ano = CAST(v_ano as unsigned) AND base.mes = CAST(v_mes as unsigned) AND base.parcela = CAST(v_parcela as unsigned);
	
	-- IRPF
	SELECT SUM(base.valor)base INTO r_irpf FROM fin_rubricas AS base
	JOIN fin_eventos fe ON fe.id = base.id_fin_eventos
	WHERE fe.tipo = 1 AND fe.evento_nome LIKE '%irpf%' AND fe.tipo = 1 AND base.`id_cad_servidores` = v_id_cad_servidor AND 
	base.ano = CAST(v_ano as unsigned) AND base.mes = CAST(v_mes as unsigned) AND base.parcela = CAST(v_parcela as unsigned);
	
	SELECT r_base, r_bruto, r_liquido, r_inss, r_rpps, r_irpf;
END */$$
DELIMITER ;

/* Procedure structure for procedure `get_fin_faixa` */

/*!50003 DROP PROCEDURE IF EXISTS  `get_fin_faixa` */;

DELIMITER $$

/*!50003 CREATE PROCEDURE `get_fin_faixa`(v_id_user varchar(11), v_tipo char(1))
    COMMENT 'Retorna os dados da faixa corrente de INSS'
BEGIN	
	declare parametro_data char(10);
	SELECT  DATE_FORMAT(STR_TO_DATE(fp.d_situacao, '%d/%m/%Y'), '%Y/%m/%d') 
		into  parametro_data
		FROM fin_parametros fp 
		JOIN mgfolha_folha.user u ON u.per_mes = fp.mes AND u.per_ano = fp.ano AND u.per_parcela = fp.parcela AND u.dominio = fp.dominio
		WHERE u.id = v_id_user;
	SELECT id, tipo, data, faixa, v_final1, v_faixa1, v_deduzir1, v_final2, v_faixa2, 
		v_deduzir2, v_final3, v_faixa3, v_deduzir3, v_final4, v_faixa4, v_deduzir4, 
		v_final5, v_faixa5, v_deduzir5, deduzir_dependente, salario_vigente, 
		inss_teto, inss_patronal, inss_rat, inss_fap, rpps_patronal 
		FROM fin_faixas
		where tipo = v_tipo AND DATE_FORMAT(STR_TO_DATE(DATA, '%d/%m/%Y'), '%Y/%m/%d') <= parametro_data
		ORDER BY DATE_FORMAT(STR_TO_DATE(DATA, '%d/%m/%Y'), '%Y/%m/%d') DESC LIMIT 1;
END */$$
DELIMITER ;

/* Procedure structure for procedure `get_holerites` */

/*!50003 DROP PROCEDURE IF EXISTS  `get_holerites` */;

DELIMITER $$

/*!50003 CREATE PROCEDURE `get_holerites`(v_url_root varchar(255), v_dominio VARCHAR(255),v_ano VARCHAR(4),v_mes VARCHAR(2),v_parcela VARCHAR(3),v_id_cad_servidores INT,v_id_cad_servidoresF INT)
    COMMENT 'Retorna os dados dos holerites'
BEGIN
	
		
	DECLARE v_id integer; 		
	DECLARE v_liquido double(11,2); 	
	declare v_extenso varchar(255);	
	declare v_token_validacao varchar(255);
	DECLARE done INT DEFAULT FALSE;
	
	DECLARE cur1Holerites CURSOR FOR  
		SELECT id, liquido, token_validacao FROM temp_holerites;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
	
	DROP TABLE IF EXISTS temp_holerites;                                
	CREATE TEMPORARY TABLE temp_holerites (
		id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,id_cad_servidor VARCHAR(40), liquido double(11,2),
		extenso varchar (255),dominio VARCHAR (255),ano VARCHAR (255),mes VARCHAR (255),parcela VARCHAR (255),
		matricula integer,d_admissao VARCHAR (255),nome VARCHAR (255),cpf VARCHAR (255),rg VARCHAR (255),
		pispasep VARCHAR (255),nomeBanco VARCHAR (255),banco_agencia VARCHAR (255),banco_agencia_digito VARCHAR (255),
		banco_conta VARCHAR (255),banco_conta_digito VARCHAR (255),banco_operacao VARCHAR (255),
		locacao VARCHAR (255),cargo VARCHAR (255),id_pccs integer,nome_pccs VARCHAR (255),margemConsignavel VARCHAR (255),
		orgao_cliente VARCHAR (255),orgao VARCHAR (255),logradouro VARCHAR (255),numero VARCHAR (255),complemento VARCHAR (255),
		bairro VARCHAR (255),cidade VARCHAR (255),uf VARCHAR (255),telefone VARCHAR (255),cnpj VARCHAR (255),mensagem VARCHAR (255),
		url_logo VARCHAR (255),url_root VARCHAR (255),token_validacao varchar(255)
	);                                
	SET @sql := concat('INSERT INTO temp_holerites (id_cad_servidor,liquido,extenso,
	dominio,ano,mes,parcela,matricula,d_admissao,nome,cpf,rg,pispasep,nomeBanco,banco_agencia,
	banco_agencia_digito,banco_conta,banco_conta_digito,banco_operacao,locacao,cargo,id_pccs,
	nome_pccs,margemConsignavel,orgao_cliente,orgao,logradouro,numero,complemento,bairro,cidade,uf,telefone,
	cnpj,mensagem,url_logo,url_root,token_validacao) (SELECT ff.id_cad_servidores id_cad_servidor,getLiquidos(cs.dominio,
	fr.ano,fr.mes,fr.parcela,fr.id_cad_servidores) liquido,"extenso",ff.dominio,ff.ano, ff.mes, ff.parcela,
	cs.matricula,cs.d_admissao, cs.nome, cs.cpf, cs.rg, cs.pispasep, cb.nome nomeBanco, cs.banco_agencia, 
	cs.banco_agencia_digito, cs.banco_conta, cs.banco_conta_digito, cs.banco_operacao, cl.departamento, 
	cg.nome cargo, cp.id_pccs, cp.nome_pccs, getMargemConsignavel(ff.ano,ff.mes,ff.parcela,cs.id) margemConsignavel, 
	o.orgao_cliente, o.orgao, o.logradouro, o.numero, o.complemento, o.bairro, o.cidade, o.uf, o.telefone, o.cnpj, fp.mensagem, 
	o.url_logo, "',v_url_root,'", TO_BASE64(CONCAT(ff.ano,"|",ff.mes,"|",ff.parcela,"|",ff.id_cad_servidores,"|",ff.dominio,"|",SUBSTR(DATABASE(),9))) token_validacao
	FROM cad_servidores cs 
	LEFT JOIN cad_bancos cb ON cb.febraban = cs.idbanco
	LEFT JOIN fin_sfuncional ff ON ff.id_cad_servidores = cs.id
	LEFT JOIN fin_rubricas fr ON fr.id_cad_servidores = ff.id_cad_servidores AND fr.mes = ff.mes AND fr.ano = ff.ano AND fr.parcela = ff.parcela
	LEFT JOIN fin_eventos ev ON fr.id_fin_eventos = ev.id AND ev.tipo = 0
	LEFT JOIN cad_departamentos cl ON cl.id = ff.id_cad_departamentos
	LEFT JOIN cad_cargos cg ON cg.id = ff.id_cad_cargos
	LEFT JOIN cad_pccs cp ON cp.id = ff.id_pccs
	LEFT JOIN orgao o ON o.dominio = cs.dominio  
	LEFT JOIN fin_parametros fp ON fp.ano = ff.ano AND fp.mes = ff.mes AND fp.parcela = ff.parcela AND fp.dominio = ff.dominio
	WHERE cast(ff.ano as integer) = cast("',v_ano,'" as integer) AND cast(ff.mes as integer) = cast("',v_mes,'" as integer) 
	AND cast(ff.parcela as integer) = cast("',v_parcela,'" as integer) AND ff.dominio = "',v_dominio,'" AND cs.id BETWEEN "',v_id_cad_servidores,'" AND "',v_id_cad_servidoresF,'" AND ff.situacao = 1
	GROUP BY fr.id_cad_servidores 
	ORDER BY cs.nome, ev.tipo, ev.id_evento)');                                
	PREPARE myStmt FROM @sql;                                
	EXECUTE myStmt;	 	
	
	OPEN cur1Holerites;
	read_loop: LOOP
		FETCH cur1Holerites INTO v_id, v_liquido, v_token_validacao;
		IF done THEN
			LEAVE read_loop;
		END IF;	
		call mgfolha_folha.getValorPorExtenso(v_liquido);
		update temp_holerites set extenso = @extenso where id = v_id;
		if ((SELECT COUNT(*) FROM mgfolha_folha.sis_shortener WHERE url LIKE CONCAT('%',v_token_validacao)) = 0) then
			insert into mgfolha_folha.sis_shortener (`url`, `shortened`) values (concat(v_url_root,v_token_validacao),CONV(ROUND((RAND() * (99999999-v_id))+v_id),12,36));
		end if;
	END LOOP;
	CLOSE cur1Holerites;
	SELECT hol.*, ss.shortened FROM temp_holerites hol
		LEFT JOIN mgfolha_folha.sis_shortener ss ON ss.`url` LIKE CONCAT('%',hol.token_validacao);
    END */$$
DELIMITER ;

/* Procedure structure for procedure `get_tempo_carteira` */

/*!50003 DROP PROCEDURE IF EXISTS  `get_tempo_carteira` */;

DELIMITER $$

/*!50003 CREATE PROCEDURE `get_tempo_carteira`(in v_dominio VARCHAR(255),IN v_ano VARCHAR(4),IN v_mes VARCHAR(2),IN v_parcela VARCHAR(3),IN v_id_cad_servidor INT,
out r_anos int, OUT r_meses INT, OUT r_dias INT, OUT r_enio INT, OUT r_enios INT, OUT r_id_fin_evento INT)
    COMMENT 'Retona o tempo de carteira o enio, os enios e o id_fin_evento'
BEGIN
	SELECT TIMESTAMPDIFF(YEAR, STR_TO_DATE(cad_servidores.d_admissao, '%d/%m/%Y'), LAST_DAY(CONCAT(fin_sfuncional.ano, '-', fin_sfuncional.mes,'-1'))) AS anos,
		TIMESTAMPDIFF(MONTH, STR_TO_DATE(cad_servidores.d_admissao, '%d/%m/%Y') + INTERVAL TIMESTAMPDIFF(YEAR,  STR_TO_DATE(cad_servidores.d_admissao, '%d/%m/%Y'), LAST_DAY(CONCAT(fin_sfuncional.ano, '-', fin_sfuncional.mes,'-1'))) YEAR , LAST_DAY(CONCAT(fin_sfuncional.ano, '-', fin_sfuncional.mes,'-1'))) AS meses,
		TIMESTAMPDIFF(DAY, STR_TO_DATE(cad_servidores.d_admissao, '%d/%m/%Y') + INTERVAL TIMESTAMPDIFF(MONTH,  STR_TO_DATE(cad_servidores.d_admissao, '%d/%m/%Y'), LAST_DAY(CONCAT(fin_sfuncional.ano, '-', fin_sfuncional.mes,'-1'))) MONTH , LAST_DAY(CONCAT(fin_sfuncional.ano, '-', fin_sfuncional.mes,'-1'))) + 1 AS dias,
		fin_sfuncional.enio
		into r_anos, r_meses, r_dias, r_enio
		FROM cad_servidores 
		JOIN fin_sfuncional ON cad_servidores.id = fin_sfuncional.id_cad_servidores and 
			cad_servidores.dominio = fin_sfuncional.dominio
		WHERE fin_sfuncional.dominio = v_dominio AND fin_sfuncional.ano = v_ano 
		AND fin_sfuncional.mes = v_mes AND fin_sfuncional.parcela = v_parcela 
		AND fin_sfuncional.id_cad_servidores = v_id_cad_servidor;
	set r_id_fin_evento = CASE
		WHEN r_enio = 1 THEN getIdEvento(v_dominio, 191)
		WHEN r_enio = 3 THEN getIdEvento(v_dominio, 192)
		WHEN r_enio = 5 THEN getIdEvento(v_dominio, 193)
		WHEN r_enio = 10 THEN getIdEvento(v_dominio, 194)
		else r_enio
	END;
	set r_enios = r_anos div r_enio;
	
    END */$$
DELIMITER ;

/* Procedure structure for procedure `get_vcto_basico` */

/*!50003 DROP PROCEDURE IF EXISTS  `get_vcto_basico` */;

DELIMITER $$

/*!50003 CREATE PROCEDURE `get_vcto_basico`(v_dominio VARCHAR(255),v_ano VARCHAR(4),v_mes VARCHAR(2),
v_parcela VARCHAR(3),v_id_cad_servidor VARCHAR(255))
BEGIN
	DECLARE r_valor, v_valor, v_margemConsignavel DECIMAL(11,2);
	DECLARE v_referencia, v_anos, v_meses, v_dias, v_i_ano_inicial, v_i_ano_final CHAR(2);
	DECLARE v_id_referencia, v_id_pccs, v_id_vinculo, v_dias_mes, v_dias_trabalhados INT;
	DECLARE v_d_admissao DATE; 
	DECLARE done INT DEFAULT FALSE; 
	-- DECLARE exec INT DEFAULT TRUE;  
	
	DECLARE cur1Referencias CURSOR FOR  
	SELECT fin_referencias.id, (fin_referencias.valor)valor, cad_classes.i_ano_inicial, cad_classes.i_ano_final 
		FROM fin_referencias 
		JOIN cad_pccs ON cad_pccs.dominio = fin_referencias.dominio 
			AND cad_pccs.id_pccs = fin_referencias.id_pccs 
		JOIN cad_classes ON cad_classes.dominio = fin_referencias.dominio 
			AND cad_classes.id_pccs = cad_pccs.id_pccs 
			AND cad_classes.id_classe = fin_referencias.id_classe 
		WHERE (cad_pccs.dominio=v_dominio) AND (fin_referencias.id_pccs=v_id_pccs) 
			AND (STR_TO_DATE(fin_referencias.data, '%d/%m/%Y') <= LAST_DAY(CONCAT(v_ano, '-', v_mes, '-1'))) 
			AND v_anos BETWEEN CAST(cad_classes.i_ano_inicial AS UNSIGNED) AND CAST(cad_classes.i_ano_final AS UNSIGNED)
		
		ORDER BY fin_referencias.id_classe DESC, fin_referencias.referencia DESC
		LIMIT 100000;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
	SET v_dias_mes = DATE_FORMAT(LAST_DAY(CONCAT(v_ano, '-', v_mes, '-1')), '%d');
	SET v_dias_trabalhados = getDiasPagarVctoBasico(v_id_cad_servidor,v_ano,v_mes);
	SET v_referencia = v_dias_trabalhados;
	SET r_valor = 0;
	set v_margemConsignavel = getMargemConsignavel(v_ano,v_mes,v_parcela,v_id_cad_servidor);
	
	SELECT fin_sfuncional.id_pccs,cad_sfuncional.id_vinculo,
		
		STR_TO_DATE(cad_servidores.d_admissao, '%d/%m/%Y')data_adm,
		TIMESTAMPDIFF(YEAR, STR_TO_DATE(cad_servidores.d_admissao, '%d/%m/%Y'), LAST_DAY(CONCAT(fin_sfuncional.ano, '-', fin_sfuncional.mes,'-1'))) AS anos,
		TIMESTAMPDIFF(MONTH, STR_TO_DATE(cad_servidores.d_admissao, '%d/%m/%Y') + INTERVAL TIMESTAMPDIFF(YEAR,  STR_TO_DATE(cad_servidores.d_admissao, '%d/%m/%Y'), LAST_DAY(CONCAT(fin_sfuncional.ano, '-', fin_sfuncional.mes,'-1'))) YEAR , LAST_DAY(CONCAT(fin_sfuncional.ano, '-', fin_sfuncional.mes,'-1'))) AS meses,
		TIMESTAMPDIFF(DAY, STR_TO_DATE(cad_servidores.d_admissao, '%d/%m/%Y') + INTERVAL TIMESTAMPDIFF(MONTH,  STR_TO_DATE(cad_servidores.d_admissao, '%d/%m/%Y'), LAST_DAY(CONCAT(fin_sfuncional.ano, '-', fin_sfuncional.mes,'-1'))) MONTH , LAST_DAY(CONCAT(fin_sfuncional.ano, '-', fin_sfuncional.mes,'-1'))) + 1 AS dias
		INTO v_id_pccs, v_id_vinculo, v_d_admissao, v_anos, v_meses, v_dias
		FROM cad_sfuncional
		JOIN cad_servidores ON cad_sfuncional.id_cad_servidores = cad_servidores.id
		JOIN fin_sfuncional ON cad_sfuncional.id_cad_servidores = fin_sfuncional.id_cad_servidores AND 
		CAST(cad_sfuncional.parcela as unsigned CAST(fin_sfuncional.ano parcela as unsignedST(cad_sfuncional.mes as unsigned) <= CAST(fin_sfuncional.mes as unsigned) AND
		CAST(cad_sfuncional.parcela as unsigned) <= CAST(fin_sfuncional.parcela as unsigned)
		
		WHERE fin_sfuncional.ano = v_ano AND fin_sfuncional.mes = v_mes AND fin_sfuncional.parcela = v_parcela 
		AND fin_sfuncional.id_cad_servidores = v_id_cad_servidor
		ORDER BY anos DESC LIMIT 1;
	
	OPEN cur1Referencias;
	read_loop: LOOP
		FETCH cur1Referencias INTO v_id_referencia, v_valor, v_i_ano_inicial, v_i_ano_final;
		IF done THEN
			LEAVE read_loop;
		END IF;
		IF !(v_anos = v_i_ano_final && (v_meses != 0 || v_dias != 0)) THEN
			SET r_valor = ((v_valor / v_dias_mes) * v_dias_trabalhados);
			IF v_i_ano_final != 0 AND v_i_ano_inicial != 99 THEN
				SET done = TRUE;
			ELSE 
				SET done = FALSE;
			END IF;
		END IF;
	END LOOP;
	CLOSE cur1Referencias;
	
	IF (v_id_vinculo < 4) THEN
		SET v_id_vinculo = (SELECT id FROM fin_eventos WHERE dominio = v_dominio AND id_evento = '001');
	ELSEIF (v_id_vinculo BETWEEN 4 AND 5) THEN
		SET v_id_vinculo = (SELECT id FROM fin_eventos WHERE dominio = v_dominio AND id_evento = '003');
	ELSEIF (v_id_vinculo = 6) THEN 
		SET v_id_vinculo = (SELECT id FROM fin_eventos WHERE dominio = v_dominio AND id_evento = '002');
	END IF;
	SELECT v_id_referencia, v_dias_trabalhados, v_id_pccs, v_id_vinculo, v_d_admissao, v_anos, v_meses, v_dias, r_valor, /*exec, */v_dias_mes, v_margemConsignavel;
END */$$
DELIMITER ;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
