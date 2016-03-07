# notes on agent for Windows

For Win32/64, agent must be installed as service:  
SC create rpm-agent displayname= "RPM Agent" binpath= "path_to\rpm-agent.exe" start= auto  
Note the spaces after "=" - this is a must!  
rpm-agent.conf must be located in c:\rpm-agent.conf
