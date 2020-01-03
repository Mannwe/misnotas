# Welcome to misnotas


<b>Description</b>

This is a simple application to record your notes in a centralized place. I developped this tool because I hated keeping 
all my personal notes distributed in a bunch of notebooks, post-its or in my smartphone. Now I am happy using this application
because I have all this information together, clean an organized.

<b>How to deploy it</b>

You need to install a PHP and MySQL servers. I recommend you to use tools like wamp, xamp, etc., so that you have everything
in your own computer. 
Once you get it done, download the application zip file from this repository and extract it in the server root folder. You will access
to the tool by writing the url http://localhost/folder_name_you_decided (I suggest http://localhost/misnotas, as this is the name of the application).

To build the database, you will find the bbdd file, named sql/bbdd.sql. Just import it from your MySQL admin or copy/paste the code in 
its SQL Editor to create the database.

<b>Signing up and logging</b>

After opening the application in your browser, you will find the item "Registrarse". Click this to sign up.
Then please enter a nickname in the "Código de usuario" field and a password in "Contraseña". Then confirm it and enter an e-mail. So far, 
the e-mail capability is not developed, so you can introduce a fake one.
If the sign up process is properly down, you will be able to login following the link and button "Iniciar sesión".

<b>How to use it</b>

The concept is very simple. You will find a structure made of nodes and notes. Nodes are build in a hierarchical way, so that you can
have parent nodes and child nodes. 

* Initially you will create your first node just clicking "Nuevo nodo" in the toolbar
* You can add new nodes to this (or other) one in the same way or add your first note clicking the menú item "Nueva nota"
* If you click one node, if you create a new one, this will be hunging from the selected one.
* You can delete nodes and notes by clicking "Borrar nodo" and "Borrar nota"

The overall behaviour is very intuitive and user-friendly, just play around!

<b>Final considerations</b>

This application is only in Spanish, but I plan a translation to English in further versions. Anyway, i think that the labels are very intuitive and understandable, or so I hope!

Just to say goodbye telling something about me. Although I am a professional developer, web applications are still a hobby. So, I am currently learning, any comments and improvement ideas will be welcome!

Enjoy!
