create sequence idsession;



create table session(
    idsession varchar primary key
);

create table session_value(
    idsession varchar references session,
    cle varchar,
    valeur json
);


insert into session values(( SELECT left(md5(random()::text), 14) || nextval('idsession')) );

-- set
insert into session_value values( '673c1de860392e2' , 'vaovao' , '{"nom": 1 }' );

-- unset
UPDATE session_value
SET valeur = valeur::jsonb - 'nom'

-- get
SELECT valeur->>'nom' as nombre FROM session_value;