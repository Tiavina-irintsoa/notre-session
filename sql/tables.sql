create sequence idsession;

create table session_value(
    idsession varchar primary key,
    valeur json
);


-- set
insert into session_value values( ( SELECT left(md5(random()::text), 14) || nextval('idsession'))  , '{"nom": 1 }' );

-- unset
UPDATE session_value
SET valeur = valeur::jsonb - 'nom' where idsession = ....

-- get
SELECT valeur->>'nom' as nombre FROM session_value;
