[![Review Assignment Due Date](https://classroom.github.com/assets/deadline-readme-button-22041afd0340ce965d47ae6ef1cefeee28c7c493a6346c4f15d667ab976d596c.svg)](https://classroom.github.com/a/wlHZ1MjT)
1. Installer Docker : https://docs.docker.com/get-started/get-docker/
2. Installer WSL avec Ubuntu sur powerShell : wsl --install -d Ubuntu
3. Faire une clé ssh : ssh-keygen -t ed25519 -C "ton_email@example.com"
4. Inserer la cle ED25519 dans GitHub : aller dans settings, ssh and gpg keys : new SSH Key, ajouter dans Key ta clé :  cat $HOME/.ssh/id_ed25519.pub, prendre le resultat et le mettre dans GitHub.
5. Cloner le projet GitHub : git clone git@github.com:infocstjean/tp1-flavy.git
6. Mettre le raccourci sail dans ton .bashrc : Faire nano ~/.bashrc, ensuite inserer cela à l'aide de CTRL 0, alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail' Pour sauvegarder faire CTRL X.
7. Ensuite faire composer install : si pas installer : 
sudo apt update,
sudo apt install composer,
sudo apt update,
sudo apt install php-xml,
composer install
8. Faire : lancer docker desktop et faire sail up
Si celui dit encore Docker is not running : faire sudo usermod -aG docker $USER, ensuite fermer la page et l'ouvrir
9. Faire : sail artisan migrate
10. Pour les tests faire sail artisan migrate --seed
11. Faire sail npm install
12. sail npm run build
