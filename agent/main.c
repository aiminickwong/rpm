
#ifdef _WIN32
#include <winsock2.h>
#include <iphlpapi.h>
#include <windows.h>
#include <errno.h>
#include <time.h>
#define MALLOC(x) HeapAlloc(GetProcessHeap(), 0, (x))
#define FREE(x) HeapFree(GetProcessHeap(), 0, (x))
SERVICE_STATUS ServiceStatus;
SERVICE_STATUS_HANDLE hStatus;
void  ServiceMain(int argc, char** argv);
void  ControlHandler(DWORD request);
#endif


#include <curl/curl.h>
#include <curl/easy.h>
#include <stdio.h>
#include <stdlib.h>
#include <libconfig.h>
#include <unistd.h>


#ifdef linux
#include <sys/ioctl.h>
#include <sys/socket.h>
#include <netinet/in.h>
#include <net/if.h>
#include <arpa/inet.h>
#include <string.h>
#define MAX_IFS 64
#endif


#define BUFFSIZE 1024
char request[BUFFSIZE];
char reply[BUFFSIZE];
int interval;

void read_state();
int read_config();
size_t curl_write( void *ptr, size_t size, size_t nmemb, void *stream)
{
    sprintf(reply,((char*)ptr));
}
int get_url(void){
    CURL *curl;
    CURLcode res;
    curl = curl_easy_init();
    if(curl) {
        curl_easy_setopt(curl, CURLOPT_URL, request);
        curl_easy_setopt(curl, CURLOPT_SSL_VERIFYPEER, 0L);
        curl_easy_setopt(curl, CURLOPT_SSL_VERIFYHOST, 0L);
        curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, curl_write);
        curl_easy_perform(curl);
        curl_easy_cleanup(curl);
  }
  return 0;
}
#ifdef _WIN32
int get_maclist(){
    PIP_ADAPTER_INFO pAdapterInfo;
    PIP_ADAPTER_INFO pAdapter = NULL;
    DWORD dwRetVal = 0;
    UINT i;
    ULONG ulOutBufLen = sizeof(IP_ADAPTER_INFO);
    pAdapterInfo = (IP_ADAPTER_INFO *)MALLOC(sizeof(IP_ADAPTER_INFO));
    if (GetAdaptersInfo(pAdapterInfo, &ulOutBufLen) == ERROR_BUFFER_OVERFLOW){
        FREE(pAdapterInfo);
        pAdapterInfo = (IP_ADAPTER_INFO *)MALLOC(ulOutBufLen);
    }
	if ((dwRetVal = GetAdaptersInfo(pAdapterInfo, &ulOutBufLen)) == NO_ERROR){
        pAdapter = pAdapterInfo;
		while (pAdapter){
		    sprintf(request + strlen(request),"mac[]=");
			for (i = 0; i < pAdapter->AddressLength; i++){
				if (i == (pAdapter->AddressLength - 1))
					sprintf(request + strlen(request),"%02X&", (int)pAdapter->Address[i]);
				else
					sprintf(request + strlen(request),"%02X:", (int)pAdapter->Address[i]);
			}

			pAdapter = pAdapter->Next;
		}
	}
	if (pAdapterInfo)
		FREE(pAdapterInfo);
    return 0;
}
void power_off(void){
    system("shutdown -s -t 0\n");
}
void delay_s(int miliseconds){
    int seconds=miliseconds*1000;
    _sleep(seconds);
}
void ServiceMain(int argc, char** argv)
{
    int error;
    ServiceStatus.dwServiceType        = SERVICE_WIN32;
    ServiceStatus.dwCurrentState       = SERVICE_START_PENDING;
    ServiceStatus.dwControlsAccepted   =  SERVICE_ACCEPT_STOP | SERVICE_ACCEPT_SHUTDOWN;
    ServiceStatus.dwWin32ExitCode      = 0;
    ServiceStatus.dwServiceSpecificExitCode = 0;
    ServiceStatus.dwCheckPoint         = 0;
    ServiceStatus.dwWaitHint           = 0;
    hStatus = RegisterServiceCtrlHandler("rpm-agent",(LPHANDLER_FUNCTION)ControlHandler);
    if (hStatus == (SERVICE_STATUS_HANDLE)0){
         return;
    }
    ServiceStatus.dwCurrentState = SERVICE_RUNNING;
    SetServiceStatus (hStatus, &ServiceStatus);
    MEMORYSTATUS memory;
    while (ServiceStatus.dwCurrentState == SERVICE_RUNNING){
    read_state();
    }
    return;
}
void ControlHandler(DWORD request){
    switch(request){
        case SERVICE_CONTROL_STOP:
            ServiceStatus.dwWin32ExitCode = 0;
            ServiceStatus.dwCurrentState  = SERVICE_STOPPED;
            SetServiceStatus (hStatus, &ServiceStatus);
            return;

        case SERVICE_CONTROL_SHUTDOWN:
            ServiceStatus.dwWin32ExitCode = 0;
            ServiceStatus.dwCurrentState  = SERVICE_STOPPED;
            SetServiceStatus (hStatus, &ServiceStatus);
            return;

        default:
            break;
    }
    SetServiceStatus (hStatus,  &ServiceStatus);
    return;
}
int daemonize(void){
    if(read_config())
        return 0;
    sprintf(request + strlen(request),"/rpm/get_pm.php?");
    get_maclist();
    SERVICE_TABLE_ENTRY ServiceTable[2];
    ServiceTable[0].lpServiceName = "rpm-agent";
    ServiceTable[0].lpServiceProc = (LPSERVICE_MAIN_FUNCTION)ServiceMain;
    ServiceTable[1].lpServiceName = NULL;
    ServiceTable[1].lpServiceProc = NULL;
    StartServiceCtrlDispatcher(ServiceTable);
    }
#endif
#ifdef linux
int get_maclist(){
    struct ifreq *ifr, *ifend;
    struct ifreq ifreq;
    struct ifconf ifc;
    struct ifreq ifs[MAX_IFS];
    int SockFD;
    SockFD = socket(AF_INET, SOCK_DGRAM, 0);
    ifc.ifc_len = sizeof(ifs);
    ifc.ifc_req = ifs;
    if (ioctl(SockFD, SIOCGIFCONF, &ifc) < 0){
        printf("ioctl(SIOCGIFCONF): %m\n");
        return 0;
    }
    ifend = ifs + (ifc.ifc_len / sizeof(struct ifreq));
    for (ifr = ifc.ifc_req; ifr < ifend; ifr++){
        if (ifr->ifr_addr.sa_family == AF_INET){
                strncpy(ifreq.ifr_name, ifr->ifr_name,sizeof(ifreq.ifr_name));
                if (ioctl (SockFD, SIOCGIFHWADDR, &ifreq) < 0){
                  printf("SIOCGIFHWADDR(%s): %m\n", ifreq.ifr_name);
                  return 0;
                }
        if (strcmp(ifreq.ifr_name,"lo") != 0){
            sprintf(request + strlen(request), "mac[]=%02x:%02x:%02x:%02x:%02x:%02x&",
            (int) ((unsigned char *) &ifreq.ifr_hwaddr.sa_data)[0],
            (int) ((unsigned char *) &ifreq.ifr_hwaddr.sa_data)[1],
            (int) ((unsigned char *) &ifreq.ifr_hwaddr.sa_data)[2],
            (int) ((unsigned char *) &ifreq.ifr_hwaddr.sa_data)[3],
            (int) ((unsigned char *) &ifreq.ifr_hwaddr.sa_data)[4],
            (int) ((unsigned char *) &ifreq.ifr_hwaddr.sa_data)[5]);
        }
    }

    }
    return 0;
}
void power_off(void){
    system("shutdown -h now \n");
}
void delay_s(int seconds){
    sleep(seconds);
}
void daemonize(){
    if(read_config())
        exit(0);
    sprintf(request + strlen(request),"/get_pm.php?");
    get_maclist();
    pid_t mypid;
    FILE *pid;
    mypid=fork();
    if (mypid){
        pid=fopen("rpm-agent.pid","w");
        fprintf(pid,"%i",mypid);
        exit(0);
    }
    while(1)
        read_state();
}
#endif
int read_config(){
    char cwd[1024];
    #ifdef _WIN32
    sprintf(cwd,"c:\\rpm-agent.conf");
    #endif // _WIN32
    #ifdef linux
    sprintf(cwd, "/etc/rpm-agent/rpm-agent.conf");
    #endif // linux
    config_t cfg, *cf;
    const config_setting_t *retries;
    const char *rpm_service = NULL;
    int count, n, query_interval;
    cf = &cfg;
    config_init(cf);


    if (!config_read_file(cf,cwd)) {
        fprintf(stderr, "%s:%d - %s\n",
            config_error_file(cf),
            config_error_line(cf),
            config_error_text(cf));
        config_destroy(cf);
        return(EXIT_FAILURE);
    }
    if (config_lookup_string(cf, "rpm_service", &rpm_service))
        sprintf(request,rpm_service);
    if (config_lookup_int(cf, "query_interval", &query_interval))
        interval=query_interval;
    config_destroy(cf);
    return 0;
}
void read_state(){
    while (get_url());
        if (strcmp(reply,"POWER_OFF") == 0){
            power_off();
        }
    delay_s(interval);
}
int main(int argc, char **argv){
    daemonize();
	return 0;
}

