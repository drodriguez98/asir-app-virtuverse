#!/bin/bash

# echo "Recogiendo parámetros"

nome="$1"

# echo "Deteniendo el contenedor"

lxc-stop $nome

if [ $? -ne 0 ]; then

	echo "Erro ao deter o contenedor"

else

	echo "OK"

fi
