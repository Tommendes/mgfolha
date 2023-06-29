update servidores set sexo = 0 where sexo = 'M';
update servidores set sexo = 1 where sexo = 'F';
update servidores set sexo = 0 where sexo not in ('0', '1');

update dependentes set sexo = 0 where sexo = 'M';
update dependentes set sexo = 1 where sexo = 'F';
update dependentes set sexo = 0 where sexo not in ('0', '1');