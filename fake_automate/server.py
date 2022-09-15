import math
import random
from time import sleep
from turtle import delay
from pyModbusTCP.server import ModbusServer, DataBank
from random import uniform
import json
import warnings
warnings.filterwarnings("ignore", category=DeprecationWarning) 
from dotenv import load_dotenv
import argparse
load_dotenv()
import os

parser = argparse.ArgumentParser()
parser.add_argument("--config",help="definir fichier de qui contient les variables d'env")
parser.add_argument("--var",help="definir variable d'env qui porte le nom de fichier de configuration ")
args = parser.parse_args()



    
if args.config:
        token = os.environ.get(args.var)
        with open(token,'r') as f:
                data = json.load(f)

        def initialiser_adresse_register(x):
                return [data["mot"][x]["adresse"]]
                
        def initialiser_adresse_coil(x):
                return [data["bit"][x]["adresse"]]
                
        def acceder_adresse_register(x):
                return data["mot"][x]["adresse"]

        def acceder_adresse_coil(x):
                return data["bit"][x]["adresse"]

        def acceder_valeur_register(x):
                return data["mot"][x]["valeur"]

        def acceder_valeur_coil(x):
                return data["bit"][x]["valeur"]
        def valeursinu(x):
                return ((math.cos(x/100)+1)*2+7)*100



        adresse_ip = data["serveur"][0]["adresse"]
        port = data["serveur"][0]["port"]

        server = ModbusServer(adresse_ip,port,no_block=True)


        try:
                print("Start server")
                server.start()
                print("Server is online")
                

                second = 0
                sleepTime = 2
                while True:
                        second = second +1
                        for i in range  (len(data["mot"])):
                                initialiser_adresse_register(i)
                                DataBank.set_words(acceder_adresse_register(i),[valeursinu(second)])
                                
                        for i in range  (len(data["bit"])):
                        
                                initialiser_adresse_coil(i)
                                DataBank.set_bits(acceder_adresse_coil(i),[random.choice([True, False])])
                        sleep(sleepTime)
                
                        
                        
                        
                        
                        
        except:
                print("shutdown server")
                server.stop
                print("server offline")
