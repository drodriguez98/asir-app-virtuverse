#!/bin/bash

# echo "Recogiendo parámetros"

nome="$1"
nick="$2"
password="$3"
ipGenerada="$4"
template="$5"
rede="$6"

lvcreate -s lxc/$template -n $nome.$nick>>/var/log/mkcontainer.log 2>&1

if [ $? -ne 0 ]; then
	echo "Erro ao crear o contenedor"
else
	lvchange -kn -a y lxc/$nome.$nick>>/var/log/mkcontainer.log 2>&1
	if [ $? -ne 0 ]; then
        	echo "Erro ao activar o contenedor"
	else
		mkdir -p /tmp/configdir
        	mount /dev/lxc/$nome.$nick /tmp/configdir>>/var/log/mkcontainer.log 2>&1
        	if [ $? -ne 0 ]; then
                	echo "Erro montando a carpeta temporal"
       		 else
			echo $nome > /tmp/configdir/etc/hostname
			if [ $? -ne 0 ]; then
				echo "Erro poñendo o hostname"
			else
				echo nameserver 8.8.8.8 > /tmp/configdir/etc/resolv.conf
				if [ $? -ne 0 ]; then
					echo "Erro poñendo o nameserver"
				else
					mkdir -p /var/lib/lxc/$nome.$nick
					mkdir -p /var/lib/lxc/$nome.$nick/rootfs
					sed -e "s/{{nome}}/$nome/g; s/{{nick}}/$nick/g; s/{{ipGenerada}}/$ipGenerada/g; s/{{template}}/$template/g; s/{{rede}}/$rede/g" /home/adminlxc/scripts/configTemplate > /var/lib/lxc/$nome.$nick/config
					if [ $? -ne 0 ]; then
						echo "Erro creando o arquivo config e rootfs"
					else
						chroot /tmp/configdir useradd -m -p $password $nick -s /bin/bash
						if [ $? -ne 0 ]; then
							echo "Erro creando o usuario"
						else
							umount /tmp/configdir
							echo "OK"
						fi
					fi
				fi
			fi
		fi
	fi
fi
