/*
SQLyog Professional v12.12 (64 bit)
MySQL - 10.4.8-MariaDB : Database - mgfolha_folha
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/* Procedure structure for procedure `getExplodeString` */

/*!50003 DROP PROCEDURE IF EXISTS  `getExplodeString` */;

DELIMITER $$

/*!50003 CREATE PROCEDURE `getExplodeString`(pDelim VARCHAR(32), pStr TEXT)
BEGIN
	DROP TABLE IF EXISTS temp_explode;                                
	CREATE TEMPORARY TABLE temp_explode (id INT AUTO_INCREMENT PRIMARY KEY NOT NULL, word VARCHAR(40));                                
	SET @sql := CONCAT('INSERT INTO temp_explode (word) VALUES (', REPLACE(QUOTE(pStr), pDelim, '\'), (\''), ')');                                
	PREPARE myStmt FROM @sql;                                
	EXECUTE myStmt;
	
/* Exemplo de uso da procedure
	SET @str  = "The quick brown fox jumped over the lazy dog"; 
	SET @delim = " "; 
	CALL explode(@delim,@str);
	SELECT id,word FROM temp_explode;
*/
    END */$$
DELIMITER ;

/* Procedure structure for procedure `getValorPorExtenso` */

/*!50003 DROP PROCEDURE IF EXISTS  `getValorPorExtenso` */;

DELIMITER $$

/*!50003 CREATE PROCEDURE `getValorPorExtenso`(valor VARCHAR(255))
    COMMENT 'Retorna um extenso do valor informado na variável valor'
BEGIN
	DECLARE grupos INT DEFAULT 0;
	DECLARE gruposFinal INT DEFAULT 0;
    	
	SET @z = 0;  
	
	SET @str  = REPLACE(FORMAT(valor, 2),",","."); 
	SET @delim = "."; 
	CALL getExplodeString(@delim,@str);
	UPDATE temp_explode SET word = LPAD(word, 3, "0");
	-- @fim identifica onde que deve se dar junção de centenas por "e" ou por "," ;)
        SELECT COUNT(*) FROM temp_explode INTO grupos;
        SELECT word INTO gruposFinal FROM temp_explode WHERE id = grupos;
        
        IF (gruposFinal > 0) THEN
		SET gruposFinal = 1;
	ELSE
		SET gruposFinal = 2;
	END IF;
	SET @fim = grupos - gruposFinal;
	
	-- percorrer e nomear os valores
        SET @rt = "";
        SET @rc = "";
        SET @r = "";
        SET @gruposLoop = 1;
	read_loop: LOOP
		IF @gruposLoop -1 < grupos THEN
			SELECT word INTO @valor FROM temp_explode WHERE id = @gruposLoop;
			-- centena do grupo
			IF ((@valor > 100) && (@valor < 200)) THEN
				SET @rc = "cento";
			ELSE
				SELECT ELT(SUBSTR(@valor,1,1) + 1, "","cem","duzentos","trezentos","quatrocentos","quinhentos","seiscentos","setecentos","oitocentos","novecentos") INTO @rc;
			END IF;
			-- dezena do grupo
			IF (SUBSTR(@valor,2,1) < 2) THEN
				SET @rd = "";
			ELSE
				SELECT ELT(SUBSTR(@valor,2,1) + 1, "","dez","vinte","trinta","quarenta","cinquenta","sessenta","setenta","oitenta","noventa") INTO @rd;
			END IF;
			-- unidade do grupo
			SET @ru = "";
			IF (@valor > 0) THEN
				IF SUBSTR(@valor,2,1) = 1 THEN
					SELECT ELT(SUBSTR(@valor,3,1) + 1, "dez","onze","doze","treze","quatorze","quinze","dezesseis","dezessete","dezoito","dezenove") INTO @ru;					
				ELSE
					SELECT ELT(SUBSTR(@valor,3,1) + 1, "","um","dois","três","quatro","cinco","seis","sete","oito","nove") INTO @ru;	
				END IF;
			END IF;
			-- agrupamento da centena
			IF (length(trim(@rc)) > 0 and (LENGTH(TRIM(@rd)) > 0 OR LENGTH(TRIM(@ru)) > 0)) THEN
				SET @r = CONCAT(@rc, " e ");
			ELSE
				SET @r = CONCAT(@rc, "");
			END IF;
			SET @r = CONCAT(@r, @rd);
			-- agrupamento da dezena
			IF (LENGTH(TRIM(@rd)) > 0 AND LENGTH(TRIM(@ru)) > 0) THEN
				SET @r = CONCAT(@r, " e ");
			ELSE
				SET @r = CONCAT(@r, "");
			END IF;
			SET @r = CONCAT(@r, @ru);
			SET @t = grupos - @gruposLoop + 1;
			IF @valor > 1 THEN
				SELECT ELT(@t, "centavos","reais","mil","milhões","bilhões","trilhões","quatrilhões") INTO @numeral;
			ELSE
				SELECT ELT(@t, "centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão") INTO @numeral;
			END IF;
			
			IF LENGTH(TRIM(@r)) > 0 THEN
				SET @r = CONCAT(@r, " ", @numeral);
			END IF;				
			
			IF @valor = "000" THEN
				SET @z = @z + 1;
			ELSE
				SET @z = @z - 1;
			END IF;
			
			IF @t = 1 AND @z > 0 AND SUBSTR(@valor,1,1) > 0 THEN
				SELECT ELT(SUBSTR(@valor,3,1) + 1, "centavos","reais","mil","milhões","bilhões","trilhões","quatrilhões") INTO @plural;
				SET @r = CONCAT(@r,IF(@z>1," de ", ""),@plural);
			END IF;
			
--			IF LENGTH(TRIM(@r)) > 0 THEN
			IF((@gruposLoop -1 > 0 && @gruposLoop -1 <= @fim && SUBSTR(@valor,1,1) > 0 && @z < 1)) then
				if (@gruposLoop -1 < @fim) then
					SET @rt = CONCAT(@rt,", ",@r);
				else
					SET @rt = CONCAT(@rt," ",@r);
				end if;
			else
				if ((LENGTH(TRIM(@rt)) > 0) and (LENGTH(TRIM(@r)) > 0)) then
					SET @rt = CONCAT(@rt," e ",@r);
				ELSEIF ((LENGTH(TRIM(@rt)) <= 0) AND (LENGTH(TRIM(@r)) > 0)) THEN
					SET @rt = CONCAT(@r);
				end if;
			end if;
--			END IF;
					
			SET @gruposLoop = @gruposLoop + 1;	
		else		
			LEAVE read_loop;
		END IF;	
	END LOOP;
	IF valor IS NULL or CAST(valor AS FLOAT) = 0 THEN
		SET @rt = "zero";
	END IF;
	
	-- SELECT TRIM(SUBSTRING(TRIM(@rt),2)) extenso;
	SELECT TRIM(@rt) into @extenso;
	
END */$$
DELIMITER ;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
