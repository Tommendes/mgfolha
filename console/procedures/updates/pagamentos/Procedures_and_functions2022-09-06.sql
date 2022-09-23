/*
SQLyog Professional v12.12 (64 bit)
MySQL - 10.4.24-MariaDB : Database - mgfolha_igaci
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
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
	CAST(cad_sfuncional.ano AS INT) <= CAST(fin_sfuncional.ano AS INT) AND CAST(cad_sfuncional.mes AS INT) <= CAST(fin_sfuncional.mes AS INT) AND
	CAST(cad_sfuncional.parcela AS INT) <= CAST(fin_sfuncional.parcela AS INT)
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
	WHERE fe.tipo = 0 AND fe.evento_nome LIKE '%vencimento%' AND base.`id_cad_servidores` = v_id_cad_servidor AND base.ano = CAST(v_ano AS INT) AND 
	base.mes = CAST(v_mes AS INT) AND base.parcela = CAST(v_parcela AS INT);
	
	-- salário bruto
	SELECT SUM(base.valor)base INTO r_bruto FROM fin_rubricas AS base
	JOIN fin_eventos fe ON fe.id = base.id_fin_eventos
	WHERE fe.tipo = 0 AND base.`id_cad_servidores` = v_id_cad_servidor AND base.ano = CAST(v_ano AS INT) AND 
	base.mes = CAST(v_mes AS INT) AND base.parcela = CAST(v_parcela AS INT);
	
	-- descontos
	SELECT SUM(base.valor)base INTO v_descontos FROM fin_rubricas AS base
	JOIN fin_eventos fe ON fe.id = base.id_fin_eventos
	WHERE fe.tipo = 1 AND base.`id_cad_servidores` = v_id_cad_servidor AND base.ano = CAST(v_ano AS INT) AND 
	base.mes = CAST(v_mes AS INT) AND base.parcela = CAST(v_parcela AS INT);
	set r_liquido = r_bruto - v_descontos;
	
	-- INSS
	SELECT SUM(base.valor)base INTO r_inss FROM fin_rubricas AS base
	JOIN fin_eventos fe ON fe.id = base.id_fin_eventos
	WHERE fe.tipo = 1 AND fe.evento_nome LIKE '%inss%' AND fe.tipo = 1 AND base.`id_cad_servidores` = v_id_cad_servidor AND 
	base.ano = CAST(v_ano AS INT) AND base.mes = CAST(v_mes AS INT) AND base.parcela = CAST(v_parcela AS INT);
	
	-- RPPS
	SELECT SUM(base.valor)base INTO r_rpps FROM fin_rubricas AS base
	JOIN fin_eventos fe ON fe.id = base.id_fin_eventos
	WHERE fe.tipo = 1 AND fe.evento_nome LIKE '%imprec%' AND fe.tipo = 1 AND base.`id_cad_servidores` = v_id_cad_servidor AND 
	base.ano = CAST(v_ano AS INT) AND base.mes = CAST(v_mes AS INT) AND base.parcela = CAST(v_parcela AS INT);
	
	-- IRPF
	SELECT SUM(base.valor)base INTO r_irpf FROM fin_rubricas AS base
	JOIN fin_eventos fe ON fe.id = base.id_fin_eventos
	WHERE fe.tipo = 1 AND fe.evento_nome LIKE '%irpf%' AND fe.tipo = 1 AND base.`id_cad_servidores` = v_id_cad_servidor AND 
	base.ano = CAST(v_ano AS INT) AND base.mes = CAST(v_mes AS INT) AND base.parcela = CAST(v_parcela AS INT);
	
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
		CAST(cad_sfuncional.ano AS INT) <= CAST(fin_sfuncional.ano AS INT) AND CAST(cad_sfuncional.mes AS INT) <= CAST(fin_sfuncional.mes AS INT) AND
		CAST(cad_sfuncional.parcela AS INT) <= CAST(fin_sfuncional.parcela AS INT)
		
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
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
