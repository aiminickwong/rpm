Remote Power Management
=======

RPM uses WOL (Wake On LAN) and small agent on OS side to turn computers on or off via network.
The center of this solution is a web service - dashboard. Wake On LAN server and OS agents query the web service for the state of computers.

To install dashboard, simply unpack it into web directory and edit functions/config.php file.

rpm-server is a daemon, that must be run in the same network segment as managed computers are. This daemon is used to turn computers on via WakeOnLAN function.
Configuration file must be located in /etc/rpm-server/rpm-server.conf 
You can start rpm-server via systemd service. rpm-server.service file is provided.

rpm-agent is a daemon which queries web service and turns computers off.
Daemon supports GNU Linux and Microsoft Windows platform.
To install agent on Linux:

    cd agent
    make 
    make install

configure /etc/rpm-agent/rpm.agent file to fit your needs.
Create systemd service (rpm-agent.service is provided).

To install agent on Windows:
copy rpm-agent.exe and library files from agent/win32-bin folder to Windows computer. Copy rpm-agent.conf to c:\ drive. Create rpm-agent service:

    SC create rpm-agent displayname= "RPM Agent" binpath= "path_to\rpm-agent.exe" start= auto

### Dashboard installation

**On Debian bases systems:**

Note: you can use mysql server instead of Maria-db
apt-get install mariadb-server apache2 php git libapache2-mod-php php-mbstring php-gettext php-ldap
Create empty database/user on db server.
cd /var/www/html/
git clone https://github.com/Seitanas/rpm
cd rpm
Edit functions/config.php file to fit your needs.
Go to http://yourservename/rpm
If installation is successful, you will be redirected to login page. Default credentials are: admin/password


![RPM](http://webjail.ring.lt/rpm/001.png)
![RPM](http://webjail.ring.lt/rpm/002.png)
![RPM](http://webjail.ring.lt/rpm/003.png)
