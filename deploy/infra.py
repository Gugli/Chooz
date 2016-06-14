#!/usr/bin/python

import yaml
import sys
import os
import json
import argparse

def GetInfra( CurrentDir, InfraName, Action, HostName ):
    
    with open(CurrentDir + "/infra_" + InfraName + ".yml", 'r') as InfraFile:
        try:
            InfraYaml = yaml.load(InfraFile)
            Groups = {}
            for Host in InfraYaml:
                for Group in Host["groups"]:
                    if (Group in Groups):
                        Groups[Group].append(Host)
                    else:
                        Groups[Group] = [Host]
                
            if(Action == "List"):
                Json = {}
                for Group in Groups:
                    GroupName = Group
                    GroupHosts = []
                    for Host in Groups[Group]:
                        GroupHosts.append(Host["name"])
                    GroupVars = {}
                    Json[GroupName] = { "hosts" : GroupHosts, "vars" : GroupVars} 
            elif (Action == "Host"):
                Json = {}
                for Host in InfraYaml:
                    if (Host["name"] == HostName):
                        HostVars = {"ansible_user": Host["ansible_user"], "ansible_host": Host["ip"], "ansible_ssh_private_key_file": Host["ansible_key_private"]}
                        if ("mysql_root_password" in Host):
                            HostVars["mysql_root_password"] = Host["mysql_root_password"]
                        Json = HostVars
                        break
            
            return json.dumps(Json, sort_keys=True, indent=4,separators=(',', ': ')) + "\n"
        except yaml.YAMLError as Exception:
            return Exception

if __name__ == "__main__":

    Parser = argparse.ArgumentParser(description='Dynamic inventory for ansible.')
    Parser.add_argument('--host', dest='HostName', action='store', help='Get info from a specific host')
    Parser.add_argument('--list', dest='IsList', action='store_true', help='List machines')
    ParsedArgs = Parser.parse_args()
    
    CurrentDir = os.path.dirname(os.path.realpath(__file__))
    HostName = ParsedArgs.HostName
    IsList = ParsedArgs.IsList
    
    Action = ""
    if (IsList):
        Action = "List"
    elif (HostName):
        Action = "Host"
        
    if(Action):
        sys.stdout.write( GetInfra(CurrentDir, "local", Action, HostName) )
    else:
        Parser.print_help()