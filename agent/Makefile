all:
	g++ main.c -o rpm-agent -lcurl -lconfig
install:
	mkdir /etc/rpm-agent
	cp rpm-agent.conf /etc/rpm-agent/rpm-agent.conf
	cp rpm-agent /usr/local/bin/rpm-agent
