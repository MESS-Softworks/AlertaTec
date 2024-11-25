import { test, expect } from '@playwright/test';

// Interfaz intuitiva para realizar una denuncia
test('DenunciaAnonima', async ({ page }) => {
  await page.goto('http://localhost/alertatec/');
  await page.getByRole('button', { name: 'IR' }).click();
  await page.getByRole('link', { name: 'Formulario' }).click();
  await page.getByRole('button', { name: 'Comenzar' }).click();
  await page.locator('#anonimo_si').check();
  await page.getByRole('button', { name: 'Siguiente' }).click();
  await page.getByLabel('Persona afectada', { exact: true }).check();
  await page.getByLabel('Alumno').check();
  await page.getByLabel('¿A qué carrera perteneces?').click();
  await page.getByLabel('¿A qué carrera perteneces?').press('CapsLock');
  await page.getByLabel('¿A qué carrera perteneces?').fill('S');
  await page.getByLabel('¿A qué carrera perteneces?').press('CapsLock');
  await page.getByLabel('¿A qué carrera perteneces?').fill('Sistemas');
  await page.getByLabel('¿En qué semestre te').selectOption('7');
  await page.getByRole('button', { name: 'Siguiente' }).click();
  await page.locator('#seccion3 > input').first().check();
  await page.locator('input:nth-child(10)').check();
  await page.locator('input:nth-child(16)').check();
  await page.getByLabel('¿Cuándo ocurrió el incidente?').fill('2024-11-07');
  await page.getByLabel('Dentro de la institución').check();
  await page.getByLabel('Describir el lugar (aula,').click();
  await page.getByLabel('Describir el lugar (aula,').press('CapsLock');
  await page.getByLabel('Describir el lugar (aula,').fill('L');
  await page.getByLabel('Describir el lugar (aula,').press('CapsLock');
  await page.getByLabel('Describir el lugar (aula,').fill('Laboratorio de computo');
  await page.getByLabel('7. Indica el número de').selectOption('1');
  await page.getByRole('radio', { name: 'Alumno' }).check();
  await page.getByLabel('Proporciona el nombre del agresor 1:').click();
  await page.getByLabel('Proporciona el nombre del agresor 1:').press('CapsLock');
  await page.getByLabel('Proporciona el nombre del agresor 1:').fill('A');
  await page.getByLabel('Proporciona el nombre del agresor 1:').press('CapsLock');
  await page.getByLabel('Proporciona el nombre del agresor 1:').fill('Agresor');
  await page.getByLabel('8. Proporciona una descripció').click();
  await page.getByLabel('8. Proporciona una descripció').press('CapsLock');
  await page.getByLabel('8. Proporciona una descripció').fill('D');
  await page.getByLabel('8. Proporciona una descripció').press('CapsLock');
  await page.getByLabel('8. Proporciona una descripció').fill('Descripcion del hecho');
  await page.getByRole('button', { name: 'Siguiente' }).click();
  await page.getByRole('radio', { name: 'No' }).check();
  await page.getByLabel('Indica el número de testigos.').selectOption('1');
  await page.getByRole('radio', { name: 'Alumno' }).check();
  await page.getByLabel('10.2 Proporciona el nombre').click();
  await page.getByLabel('10.2 Proporciona el nombre').press('CapsLock');
  await page.getByLabel('10.2 Proporciona el nombre').fill('T');
  await page.getByLabel('10.2 Proporciona el nombre').press('CapsLock');
  await page.getByLabel('10.2 Proporciona el nombre').fill('Testigo');
  await page.getByRole('button', { name: 'Siguiente' }).click();
  await page.getByLabel('No, prefiero no recibir').check();
  await page.getByRole('radio', { name: 'No', exact: true }).check();
  await page.getByRole('button', { name: 'Enviar denuncia' }).click();
});

test('DenunciaNoAnonima', async ({ page }) => {
  await page.goto('http://localhost/alertatec/');
  await page.getByRole('button', { name: 'IR' }).click();
  await page.getByRole('link', { name: 'Formulario' }).click();
  await page.getByRole('button', { name: 'Comenzar' }).click();
  await page.locator('#anonimo_no').check();
  await page.getByLabel('Nombre completo:').click();
  await page.getByLabel('Nombre completo:').fill('S');
  await page.getByLabel('Nombre completo:').press('CapsLock');
  await page.getByLabel('Nombre completo:').fill('Sebastian ');
  await page.getByLabel('Nombre completo:').press('CapsLock');
  await page.getByLabel('Nombre completo:').fill('Sebastian R');
  await page.getByLabel('Nombre completo:').press('CapsLock');
  await page.getByLabel('Nombre completo:').fill('Sebastian Rodriguez ');
  await page.getByLabel('Nombre completo:').press('CapsLock');
  await page.getByLabel('Nombre completo:').fill('Sebastian Rodriguez C');
  await page.getByLabel('Nombre completo:').press('CapsLock');
  await page.getByLabel('Nombre completo:').fill('Sebastian Rodriguez Cervantes');
  await page.getByLabel('Correo Electrónico (').fill('sebasrdz88@gmail.com');
  await page.getByLabel('Número telefónico (opcional):').fill('8719999999');
  await page.getByRole('button', { name: 'Siguiente' }).click();
  await page.getByLabel('Persona afectada', { exact: true }).check();
  await page.getByLabel('Alumno').check();
  await page.getByLabel('¿A qué carrera perteneces?').click();
  await page.getByLabel('¿A qué carrera perteneces?').press('CapsLock');
  await page.getByLabel('¿A qué carrera perteneces?').fill('S');
  await page.getByLabel('¿A qué carrera perteneces?').press('CapsLock');
  await page.getByLabel('¿A qué carrera perteneces?').fill('Sistemas');
  await page.getByLabel('¿En qué semestre te').selectOption('7');
  await page.getByRole('button', { name: 'Siguiente' }).click();
  await page.locator('#seccion3 > input').first().check();
  await page.locator('input:nth-child(10)').check();
  await page.locator('input:nth-child(16)').check();
  await page.getByLabel('¿Cuándo ocurrió el incidente?').fill('2024-11-07');
  await page.getByLabel('Dentro de la institución').check();
  await page.getByLabel('Describir el lugar (aula,').click();
  await page.getByLabel('Describir el lugar (aula,').press('CapsLock');
  await page.getByLabel('Describir el lugar (aula,').fill('L');
  await page.getByLabel('Describir el lugar (aula,').press('CapsLock');
  await page.getByLabel('Describir el lugar (aula,').fill('Laboratorio de computo');
  await page.getByLabel('7. Indica el número de').selectOption('1');
  await page.getByRole('radio', { name: 'Alumno' }).check();
  await page.getByLabel('Proporciona el nombre del agresor 1:').click();
  await page.getByLabel('Proporciona el nombre del agresor 1:').press('CapsLock');
  await page.getByLabel('Proporciona el nombre del agresor 1:').fill('A');
  await page.getByLabel('Proporciona el nombre del agresor 1:').press('CapsLock');
  await page.getByLabel('Proporciona el nombre del agresor 1:').fill('Agresor');
  await page.getByLabel('8. Proporciona una descripció').click();
  await page.getByLabel('8. Proporciona una descripció').press('CapsLock');
  await page.getByLabel('8. Proporciona una descripció').fill('D');
  await page.getByLabel('8. Proporciona una descripció').press('CapsLock');
  await page.getByLabel('8. Proporciona una descripció').fill('Descripcion del hecho');
  await page.getByRole('button', { name: 'Siguiente' }).click();
  await page.getByRole('radio', { name: 'No' }).check();
  await page.getByLabel('Indica el número de testigos.').selectOption('1');
  await page.getByRole('radio', { name: 'Alumno' }).check();
  await page.getByLabel('10.2 Proporciona el nombre').click();
  await page.getByLabel('10.2 Proporciona el nombre').press('CapsLock');
  await page.getByLabel('10.2 Proporciona el nombre').fill('T');
  await page.getByLabel('10.2 Proporciona el nombre').press('CapsLock');
  await page.getByLabel('10.2 Proporciona el nombre').fill('Testigo');
  await page.getByRole('button', { name: 'Siguiente' }).click();
  await page.getByLabel('No, prefiero no recibir').check();
  await page.getByRole('radio', { name: 'No', exact: true }).check();
  await page.getByRole('button', { name: 'Enviar denuncia' }).click();
});

test('CampoSinLlenar', async ({ page }) => {
await page.goto('http://localhost/alertatec/');
await page.getByRole('button', { name: 'IR' }).click();
await page.getByRole('button', { name: 'Ir a formulario' }).click();
await page.getByRole('button', { name: 'Comenzar' }).click();
page.once('dialog', dialog => {
    console.log(`Dialog message: ${dialog.message()}`);
    dialog.dismiss().catch(() => {});
  });
  await page.locator('#seccion1').getByRole('button', { name: 'Siguiente' }).click();
});


//Anonimato y protección de datos
test('DenunciaAnonimaBD', async ({ page }) => {
  await page.goto('http://localhost/alertatec/');
  await page.getByRole('button', { name: 'IR' }).click();
  await page.getByRole('link', { name: 'Formulario' }).click();
  await page.getByRole('button', { name: 'Comenzar' }).click();
  await page.locator('#anonimo_si').check();
  await page.getByRole('button', { name: 'Siguiente' }).click();
  await page.getByLabel('Persona afectada', { exact: true }).check();
  await page.getByLabel('Alumno').check();
  await page.getByLabel('¿A qué carrera perteneces?').click();
  await page.getByLabel('¿A qué carrera perteneces?').press('CapsLock');
  await page.getByLabel('¿A qué carrera perteneces?').fill('S');
  await page.getByLabel('¿A qué carrera perteneces?').press('CapsLock');
  await page.getByLabel('¿A qué carrera perteneces?').fill('Sistemas');
  await page.getByLabel('¿En qué semestre te').selectOption('7');
  await page.getByRole('button', { name: 'Siguiente' }).click();
  await page.locator('#seccion3 > input').first().check();
  await page.locator('input:nth-child(10)').check();
  await page.locator('input:nth-child(16)').check();
  await page.getByLabel('¿Cuándo ocurrió el incidente?').fill('2024-11-07');
  await page.getByLabel('Dentro de la institución').check();
  await page.getByLabel('Describir el lugar (aula,').click();
  await page.getByLabel('Describir el lugar (aula,').press('CapsLock');
  await page.getByLabel('Describir el lugar (aula,').fill('L');
  await page.getByLabel('Describir el lugar (aula,').press('CapsLock');
  await page.getByLabel('Describir el lugar (aula,').fill('Laboratorio de computo');
  await page.getByLabel('7. Indica el número de').selectOption('1');
  await page.getByRole('radio', { name: 'Alumno' }).check();
  await page.getByLabel('Proporciona el nombre del agresor 1:').click();
  await page.getByLabel('Proporciona el nombre del agresor 1:').press('CapsLock');
  await page.getByLabel('Proporciona el nombre del agresor 1:').fill('A');
  await page.getByLabel('Proporciona el nombre del agresor 1:').press('CapsLock');
  await page.getByLabel('Proporciona el nombre del agresor 1:').fill('Agresor');
  await page.getByLabel('8. Proporciona una descripció').click();
  await page.getByLabel('8. Proporciona una descripció').press('CapsLock');
  await page.getByLabel('8. Proporciona una descripció').fill('D');
  await page.getByLabel('8. Proporciona una descripció').press('CapsLock');
  await page.getByLabel('8. Proporciona una descripció').fill('Descripcion del hecho');
  await page.getByRole('button', { name: 'Siguiente' }).click();
  await page.getByRole('radio', { name: 'No' }).check();
  await page.getByLabel('Indica el número de testigos.').selectOption('1');
  await page.getByRole('radio', { name: 'Alumno' }).check();
  await page.getByLabel('10.2 Proporciona el nombre').click();
  await page.getByLabel('10.2 Proporciona el nombre').press('CapsLock');
  await page.getByLabel('10.2 Proporciona el nombre').fill('T');
  await page.getByLabel('10.2 Proporciona el nombre').press('CapsLock');
  await page.getByLabel('10.2 Proporciona el nombre').fill('Testigo');
  await page.getByRole('button', { name: 'Siguiente' }).click();
  await page.getByLabel('Sí, deseo recibir').check();
  await page.locator('#correoDA').click();
  await page.locator('#correoDA').fill('sebasrdz88');
  await page.locator('#correoDA').press('Alt+6');
  await page.locator('#correoDA').press('Alt+4');
  await page.locator('#correoDA').fill('sebasrdz88@gmail.com');
  await page.getByRole('radio', { name: 'No', exact: true }).check();
  await page.getByRole('button', { name: 'Enviar denuncia' }).click();

  await page.goto('http://localhost/phpmyadmin/index.php?route=/database/structure&db=alertatec');
  await page.getByRole('cell', { name: 'denunciante', exact: true }).getByRole('link').click();
});

//Denuncia con opciones personalizadas
test('DenunciaOpciones', async ({ page }) => {
await page.goto('http://localhost/alertatec/');
  await page.getByRole('button', { name: 'IR' }).click();
  await page.getByRole('link', { name: 'Formulario' }).click();
  await page.getByRole('button', { name: 'Comenzar' }).click();
  await page.locator('#anonimo_si').check();
  await page.getByRole('button', { name: 'Siguiente' }).click();
  await page.getByLabel('Persona afectada', { exact: true }).check();
  await page.getByLabel('Alumno').check();
  await page.getByLabel('¿A qué carrera perteneces?').click();
  await page.getByLabel('¿A qué carrera perteneces?').press('CapsLock');
  await page.getByLabel('¿A qué carrera perteneces?').fill('S');
  await page.getByLabel('¿A qué carrera perteneces?').press('CapsLock');
  await page.getByLabel('¿A qué carrera perteneces?').fill('Sistemas');
  await page.getByLabel('¿En qué semestre te').selectOption('7');
  await page.getByRole('button', { name: 'Siguiente' }).click();

  await page.locator('input:nth-child(19)').first().check();
  await page.locator('input:nth-child(7)').check();
  await page.locator('#seccion3 > input').first().check();
  await page.locator('input:nth-child(19)').first().uncheck();
  await page.getByLabel('¿Cuándo ocurrió el incidente?').fill('2024-11-07');
  await page.getByLabel('Dentro de la institución').check();
  await page.getByLabel('Describir el lugar (aula,').click();
  await page.getByLabel('Describir el lugar (aula,').press('CapsLock');
  await page.getByLabel('Describir el lugar (aula,').fill('L');
  await page.getByLabel('Describir el lugar (aula,').press('CapsLock');
  await page.getByLabel('Describir el lugar (aula,').fill('Laboratorio de computo');
  await page.getByLabel('7. Indica el número de').selectOption('1');
  await page.getByRole('radio', { name: 'Alumno' }).check();
  await page.getByLabel('Proporciona el nombre del agresor 1:').click();
  await page.getByLabel('Proporciona el nombre del agresor 1:').press('CapsLock');
  await page.getByLabel('Proporciona el nombre del agresor 1:').fill('A');
  await page.getByLabel('Proporciona el nombre del agresor 1:').press('CapsLock');
  await page.getByLabel('Proporciona el nombre del agresor 1:').fill('Agresor');
  await page.getByLabel('8. Proporciona una descripció').click();
  await page.getByLabel('8. Proporciona una descripció').press('CapsLock');
  await page.getByLabel('8. Proporciona una descripció').fill('D');
  await page.getByLabel('8. Proporciona una descripció').press('CapsLock');
  await page.getByLabel('8. Proporciona una descripció').fill('Descripcion del hecho');
  await page.getByRole('button', { name: 'Siguiente' }).click();
  await page.getByRole('radio', { name: 'No' }).check();
  await page.getByLabel('Indica el número de testigos.').selectOption('1');
  await page.getByRole('radio', { name: 'Alumno' }).check();
  await page.getByLabel('10.2 Proporciona el nombre').click();
  await page.getByLabel('10.2 Proporciona el nombre').press('CapsLock');
  await page.getByLabel('10.2 Proporciona el nombre').fill('T');
  await page.getByLabel('10.2 Proporciona el nombre').press('CapsLock');
  await page.getByLabel('10.2 Proporciona el nombre').fill('Testigo');
  await page.getByRole('button', { name: 'Siguiente' }).click();
  await page.getByLabel('Sí, deseo recibir').check();
  await page.locator('#correoDA').click();
  await page.locator('#correoDA').fill('sebasrdz88');
  await page.locator('#correoDA').press('Alt+6');
  await page.locator('#correoDA').press('Alt+4');
  await page.locator('#correoDA').fill('sebasrdz88@gmail.com');
  await page.getByRole('radio', { name: 'No', exact: true }).check();
  await page.getByRole('button', { name: 'Enviar denuncia' }).click();
});

//Log In
//ADMINISTRADOR 
/*
test('LoginAdmin', async ({ page }) => {
await page.goto('http://localhost/alertatec/');
await page.getByRole('link').click();
await page.getByLabel('Usuario:').click();
await page.getByLabel('Usuario:').press('CapsLock');
await page.getByLabel('Usuario:').fill('A');
await page.getByLabel('Usuario:').press('CapsLock');
await page.getByLabel('Usuario:').fill('Administrador');
await page.getByLabel('Contraseña:').click();
await page.getByLabel('Contraseña:').fill('adminlag1');
await page.getByRole('button', { name: 'Administrador', exact: true }).click();
await page.getByText('☰').click();
await page.getByRole('button', { name: 'Cerrar sesión' }).click();
await page.goto('http://localhost/alertatec/login.php');
});

test('LoginAdminContraseñaInvalida', async ({ page }) => {
  await page.goto('http://localhost/alertatec/');
  await page.getByRole('link').click();
  await page.getByLabel('Usuario:').click();
  await page.getByLabel('Usuario:').press('CapsLock');
  await page.getByLabel('Usuario:').fill('A');
  await page.getByLabel('Usuario:').press('CapsLock');
  await page.getByLabel('Usuario:').fill('Administrador');
  await page.getByLabel('Contraseña:').click();
  await page.getByLabel('Contraseña:').fill('adminlag');
  await page.getByRole('button', { name: 'Administrador', exact: true }).click();
  });
  */

//Log In
//SUPER-ADMINISTRADOR 
test('LoginSuperAdmin', async ({ page }) => {
 await page.goto('http://localhost/alertatec/');
 await page.getByRole('link').click();
 await page.getByLabel('Usuario:').click();
 await page.getByLabel('Usuario:').fill('admin');
 await page.getByLabel('Contraseña:').click();
 await page.getByLabel('Contraseña:').fill('admin');
 await page.getByRole('button', { name: 'SuperAdministrador' }).click();
 await page.getByText('☰').click();
 await page.getByRole('button', { name: 'Cerrar sesión' }).click();
  });

  test('LoginSuperAdminContraseñaInvalida', async ({ page }) => {
  await page.goto('http://localhost/alertatec/');
  await page.getByRole('link').click();
  await page.getByLabel('Usuario:').click();
  await page.getByLabel('Usuario:').fill('admin');
  await page.getByLabel('Contraseña:').click();
  await page.getByLabel('Contraseña:').fill('contrasena');
  await page.getByRole('button', { name: 'SuperAdministrador' }).click();
  });

  //Recursos de Apoyo y Orientación
  test('RecursosApoyoV1', async ({ page }) => {
  await page.goto('http://localhost/alertatec/');
  await page.getByRole('button', { name: 'IR' }).click();
  await page.getByRole('link', { name: 'Recursos de apoyo' }).click();
  await page.getByText('Red Nacional de Refugios.').click();
  await page.getByRole('link', { name: 'https://rednacionalderefugios' }).click();
     });

  test('RecursosApoyoV2', async ({ page }) => {
  await page.goto('http://localhost/alertatec/');
  await page.getByRole('button', { name: 'IR' }).click();
  await page.getByRole('link', { name: 'Recursos de apoyo' }).click();
  await page.getByText('Red Nacional de Refugios.').click();
  await page.getByText('Fundación Origen.').click();
  await page.getByText('3. “Violencias en la educació').click();
  await page.getByText('Red Nacional de Refugios.').click();
      });

 //Retroalimentacion
 test('EncuestaExitosa', async ({ page }) => {
  await page.goto('http://localhost/alertatec/');
  await page.getByRole('button', { name: 'IR' }).click();
  await page.getByRole('link', { name: 'Contactanos' }).click();
  await page.getByRole('button', { name: 'SUGERENCIAS' }).click();
  await page.getByRole('button', { name: 'Siguiente' }).click();
  await page.getByLabel('¿Cómo calificarías tu').getByLabel('5').click();
  await page.getByLabel('¿Qué tan fácil te resultó').getByLabel('5').click();
  await page.getByLabel('Menos de 5 minutos').click();
  await page.getByRole('button', { name: 'Siguiente' }).click();
  await page.locator('label:nth-child(6) > .eRqjfd').first().click();
  await page.getByLabel('¿Qué tan intuitiva consideras').getByLabel('5').click();
  await page.getByLabel('Sección 1: Tipo de denuncia').click();
  await page.getByLabel('Sección 3: Detalles de la').click();
  await page.getByLabel('Sección 4: Evidencias').click();
  await page.getByLabel('Sección 3: Detalles de la').click();
  await page.getByLabel('Sección 2: Datos del').click();
  await page.getByLabel('Sección 2: Datos del').click();
  await page.getByLabel('Sección 5: Finalizar denuncia').click();
  await page.getByRole('button', { name: 'Siguiente' }).click();
  await page.getByLabel('¿Te resulta agradable el dise').getByLabel('5').click();
  await page.getByLabel('¿El uso de colores y tipograf').getByLabel('5').click();
  await page.getByRole('button', { name: 'Siguiente' }).click();
  await page.getByLabel('¿Qué tan seguro/a te sentiste').getByLabel('5').click();
  await page.getByLabel('¿El proceso de denuncia te').getByLabel('5').click();
  await page.getByRole('button', { name: 'Siguiente' }).click();
  await page.getByLabel('¿Hay alguna funcionalidad que').click();
  await page.getByLabel('¿Hay alguna funcionalidad que').press('CapsLock');
  await page.getByLabel('¿Hay alguna funcionalidad que').fill('C');
  await page.getByLabel('¿Hay alguna funcionalidad que').press('CapsLock');
  await page.getByLabel('¿Hay alguna funcionalidad que').fill('Chat de apoyo');
  await page.getByLabel('¿Qué crees que podría mejorar').click();
  await page.getByLabel('¿Qué crees que podría mejorar').press('CapsLock');
  await page.getByLabel('¿Qué crees que podría mejorar').fill('M');
  await page.getByLabel('¿Qué crees que podría mejorar').press('CapsLock');
  await page.getByLabel('¿Qué crees que podría mejorar').fill('Me parece que incluir esta mejora puede ser gran idea');
  await page.getByLabel('¿Tienes algún comentario o').click();
  await page.getByLabel('¿Tienes algún comentario o').press('CapsLock');
  await page.getByLabel('¿Tienes algún comentario o').fill('H');
  await page.getByLabel('¿Tienes algún comentario o').press('CapsLock');
  await page.getByLabel('¿Tienes algún comentario o').fill('He tenido una experiencia positiva');
  await page.getByLabel('Sí').click();
  await page.getByLabel('Submit').click();
     });

  //Contacto   
  test('Contacto', async ({ page }) => {
    await page.goto('http://localhost/alertatec/');
    await page.getByRole('button', { name: 'IR' }).click();
    await page.getByRole('link', { name: 'Contactanos' }).click();
    await page.getByRole('link', { name: 'Correo' }).click();
  });

  //Sistema de Clasificacion de reportes
  test('ClasificacionReportes', async ({ page }) => {
    await page.goto('http://localhost/alertatec/');
    await page.getByRole('link').click();
    await page.getByLabel('Usuario:').click();
    await page.getByLabel('Usuario:').press('CapsLock');
    await page.getByLabel('Usuario:').fill('S');
    await page.getByLabel('Usuario:').press('CapsLock');
    await page.getByLabel('Usuario:').fill('Sebas');
    await page.getByLabel('Usuario:').press('Tab');
    await page.getByLabel('Contraseña:').fill('sebas');
    await page.getByRole('button', { name: 'Administrador', exact: true }).click();
    await page.getByText('☰').click();
    await page.getByRole('button', { name: 'Verbal' }).click();
    await page.getByRole('button', { name: 'Física' }).click();
    await page.getByRole('button', { name: 'Sexual' }).click();
    await page.getByRole('button', { name: 'Acoso', exact: true }).click();
    await page.getByRole('button', { name: 'Discriminación' }).click();
    await page.getByRole('button', { name: 'Otros' }).click();
    await page.getByRole('button', { name: 'Hostigamiento' }).click();
  });

  //*Gestionar Reportes
  test('AprobarReporte', async ({ page }) => {
    await page.goto('http://localhost/alertatec/');
    await page.getByRole('link').click();
    await page.getByLabel('Usuario:').click();
    await page.getByLabel('Usuario:').press('CapsLock');
    await page.getByLabel('Usuario:').fill('S');
    await page.getByLabel('Usuario:').press('CapsLock');
    await page.getByLabel('Usuario:').fill('Sebas');
    await page.getByLabel('Usuario:').press('Tab');
    await page.getByLabel('Contraseña:').fill('sebas');
    await page.getByRole('button', { name: 'Administrador', exact: true }).click();
    const page1Promise = page.waitForEvent('popup');
    await page.getByRole('row', { name: '4 Ver reporte Alta No hay' }).getByRole('link').click();
    const page1 = await page1Promise;
    page.once('dialog', dialog => {
      console.log(`Dialog message: ${dialog.message()}`);
      dialog.dismiss().catch(() => {});
    });
    await page.getByRole('row', { name: '4 Ver reporte Alta No hay' }).getByRole('button').first().click();
  });

  test('DescartarReporte', async ({ page }) => {
    await page.goto('http://localhost/alertatec/');
    await page.getByRole('link').click();
    await page.getByLabel('Usuario:').click();
    await page.getByLabel('Usuario:').press('CapsLock');
    await page.getByLabel('Usuario:').fill('S');
    await page.getByLabel('Usuario:').press('CapsLock');
    await page.getByLabel('Usuario:').fill('Sebas');
    await page.getByLabel('Usuario:').press('Tab');
    await page.getByLabel('Contraseña:').fill('sebas');
    await page.getByRole('button', { name: 'Administrador', exact: true }).click();
    const page4Promise = page.waitForEvent('popup');
    await page.getByRole('row', { name: '6 Ver reporte Alta No hay' }).getByRole('link').click();
    const page4 = await page4Promise;

    page.once('dialog', dialog => {
      console.log(`Dialog message: ${dialog.message()}`);
      dialog.dismiss().catch(() => {});
    });
    await page.getByRole('row', { name: '6 Ver reporte Alta No hay' }).getByRole('button').nth(1).click();
  });


  //* Gestionar cuentas
  test('CrearCuenta', async ({ page }) => {
    await page.goto('http://localhost/alertatec/');
    await page.getByRole('link').click();
    await page.getByLabel('Usuario:').click();
    await page.getByLabel('Usuario:').fill('admin');
    await page.getByLabel('Contraseña:').click();
    await page.getByLabel('Contraseña:').fill('admin');
    await page.getByRole('button', { name: 'SuperAdministrador' }).click();

    await page.locator('#seccion1 form').filter({ hasText: 'Nombre de Usuario: Contraseña: Alta' }).locator('#correoE').click();
    await page.locator('#seccion1 form').filter({ hasText: 'Nombre de Usuario: Contraseña: Alta' }).locator('#correoE').press('CapsLock');
    await page.locator('#seccion1 form').filter({ hasText: 'Nombre de Usuario: Contraseña: Alta' }).locator('#correoE').fill('E');
    await page.locator('#seccion1 form').filter({ hasText: 'Nombre de Usuario: Contraseña: Alta' }).locator('#correoE').press('CapsLock');
    await page.locator('#seccion1 form').filter({ hasText: 'Nombre de Usuario: Contraseña: Alta' }).locator('#correoE').fill('Edson');
    await page.locator('#seccion1 form').filter({ hasText: 'Nombre de Usuario: Contraseña: Alta' }).locator('#passw').click();
    await page.locator('#seccion1 form').filter({ hasText: 'Nombre de Usuario: Contraseña: Alta' }).locator('#passw').fill('edson');
    await page.getByRole('button', { name: 'Alta' }).click();
    await page.locator('#seccion1 form').filter({ hasText: 'Nombre de Usuario: Contraseña: Alta' }).locator('#correoE').click();
    await page.locator('#seccion1 form').filter({ hasText: 'Nombre de Usuario: Contraseña: Alta' }).locator('#correoE').fill('sofia');
    await page.getByRole('button', { name: 'Alta' }).click();
    await page.locator('#seccion1 form').filter({ hasText: 'Nombre de Usuario: Contraseña: Alta' }).locator('input[type="reset"]').click();
  });

  test('ModificarCuenta', async ({ page }) => {
    await page.goto('http://localhost/alertatec/');
    await page.getByRole('link').click();
    await page.getByLabel('Usuario:').click();
    await page.getByLabel('Usuario:').fill('admin');
    await page.getByLabel('Contraseña:').click();
    await page.getByLabel('Contraseña:').fill('admin');
    await page.getByRole('button', { name: 'SuperAdministrador' }).click();

    await page.locator('#seccion1 form').filter({ hasText: 'Nombre de Usuario: Contraseña: Modificar' }).locator('#correoE').click();
    await page.locator('#seccion1 form').filter({ hasText: 'Nombre de Usuario: Contraseña: Modificar' }).locator('#correoE').press('CapsLock');
    await page.locator('#seccion1 form').filter({ hasText: 'Nombre de Usuario: Contraseña: Modificar' }).locator('#correoE').fill('E');
    await page.locator('#seccion1 form').filter({ hasText: 'Nombre de Usuario: Contraseña: Modificar' }).locator('#correoE').press('CapsLock');
    await page.locator('#seccion1 form').filter({ hasText: 'Nombre de Usuario: Contraseña: Modificar' }).locator('#correoE').fill('Edson');
    await page.locator('#seccion1 form').filter({ hasText: 'Nombre de Usuario: Contraseña: Modificar' }).locator('#passw').click();
    await page.locator('#seccion1 form').filter({ hasText: 'Nombre de Usuario: Contraseña: Modificar' }).locator('#passw').fill('scrum');
    await page.getByRole('button', { name: 'Modificar' }).click();
    await page.locator('#seccion1 form').filter({ hasText: 'Nombre de Usuario: Contraseña: Modificar' }).locator('#correoE').click();
    await page.locator('#seccion1 form').filter({ hasText: 'Nombre de Usuario: Contraseña: Modificar' }).locator('#correoE').press('CapsLock');
    await page.locator('#seccion1 form').filter({ hasText: 'Nombre de Usuario: Contraseña: Modificar' }).locator('#correoE').fill('y');
    await page.locator('#seccion1 form').filter({ hasText: 'Nombre de Usuario: Contraseña: Modificar' }).locator('#correoE').press('CapsLock');
    await page.locator('#seccion1 form').filter({ hasText: 'Nombre de Usuario: Contraseña: Modificar' }).locator('#correoE').fill('yO');
    await page.locator('#seccion1 form').filter({ hasText: 'Nombre de Usuario: Contraseña: Modificar' }).locator('#passw').click();
    await page.locator('#seccion1 form').filter({ hasText: 'Nombre de Usuario: Contraseña: Modificar' }).locator('#passw').press('CapsLock');
    await page.locator('#seccion1 form').filter({ hasText: 'Nombre de Usuario: Contraseña: Modificar' }).locator('#passw').fill('yo');
    await page.getByRole('button', { name: 'Modificar' }).click();
    await page.locator('#seccion1 form').filter({ hasText: 'Nombre de Usuario: Contraseña: Modificar' }).locator('#correoE').click();
    await page.locator('#seccion1 form').filter({ hasText: 'Nombre de Usuario: Contraseña: Modificar' }).locator('#correoE').press('CapsLock');
    await page.locator('#seccion1 form').filter({ hasText: 'Nombre de Usuario: Contraseña: Modificar' }).locator('#correoE').fill('Y');
    await page.locator('#seccion1 form').filter({ hasText: 'Nombre de Usuario: Contraseña: Modificar' }).locator('#correoE').press('CapsLock');
    await page.locator('#seccion1 form').filter({ hasText: 'Nombre de Usuario: Contraseña: Modificar' }).locator('#correoE').fill('Yo');
    await page.locator('#seccion1 form').filter({ hasText: 'Nombre de Usuario: Contraseña: Modificar' }).locator('#passw').click();
    await page.locator('#seccion1 form').filter({ hasText: 'Nombre de Usuario: Contraseña: Modificar' }).locator('#passw').fill('yo');
    await page.locator('#seccion1 form').filter({ hasText: 'Nombre de Usuario: Contraseña: Modificar' }).locator('input[type="reset"]').click();
  });

  test('EliminarCuenta', async ({ page }) => {
    await page.goto('http://localhost/alertatec/');
    await page.getByRole('link').click();
    await page.getByLabel('Usuario:').click();
    await page.getByLabel('Usuario:').fill('admin');
    await page.getByLabel('Contraseña:').click();
    await page.getByLabel('Contraseña:').fill('admin');
    await page.getByRole('button', { name: 'SuperAdministrador' }).click();

    await page.locator('#seccion1 form').filter({ hasText: 'Nombre de Usuario: Baja' }).locator('#correoE').click();
    await page.locator('#seccion1 form').filter({ hasText: 'Nombre de Usuario: Baja' }).locator('#correoE').press('CapsLock');
    await page.locator('#seccion1 form').filter({ hasText: 'Nombre de Usuario: Baja' }).locator('#correoE').fill('E');
    await page.locator('#seccion1 form').filter({ hasText: 'Nombre de Usuario: Baja' }).locator('#correoE').press('CapsLock');
    await page.locator('#seccion1 form').filter({ hasText: 'Nombre de Usuario: Baja' }).locator('#correoE').fill('Edson');
    await page.getByRole('button', { name: 'Baja' }).click();
  });