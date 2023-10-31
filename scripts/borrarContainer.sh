#!/bin/bash

# echo "Recogiendo parámetros"

nome="$1"

# echo "Borrando el contenedor"

lvremove -f lxc/$nome>>/var/log/mkcontainer.log 2>&1

if [ $? -ne 0 ]; then

	echo "Erro ao borrar o contenedor"

else

	rm -r /var/lib/lxc/$nome

	if [ $? -ne 0 ]; then

		echo "Erro ao borrar os directorios"

	else

		echo "OK"

	fi

fi
