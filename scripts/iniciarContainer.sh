#!/bin/bash

# echo "Recogiendo parámetros"

nome="$1"

# echo "Arrancando el contenedor"

lxc-start $nome

if [ $? -ne 0 ]; then

	echo "Erro ao iniciar o contedor"

else

	echo "OK"

fi
