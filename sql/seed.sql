USE finanzas;

INSERT INTO gastos_fijos(nombre,monto,metodo,activo) VALUES
('Netflix (mitad)',199.00,'TDC BBVA',1),
('Meli+ / Mercado Pago (suscripción)',300.00,'TDC BBVA',1),
('HBO',199.00,'TDC Nu',1),
('Recarga Metro (estimado base)',433.00,'TDC BBVA',1),
('Google Photos',39.00,'TDC BBVA',1),
('iCloud+ 50GB',17.00,'TDC BBVA',1),
('ChatGPT Plus',399.00,'TDC BBVA',1),
('Teléfono + Internet',550.00,'Débito BBVA',1);

INSERT INTO deudas(nombre,total_deuda,pago_mensual,saldo_actual,activo) VALUES
('Suburbia',4717.09,894.91,4717.09,1),
('Mercado Pago (deuda)',2347.90,505.42,2347.90,1);

UPDATE configuracion SET tope_libre=3000.00, bbva_corte_dia=6, bbva_limite_dia=26 WHERE id=1;
