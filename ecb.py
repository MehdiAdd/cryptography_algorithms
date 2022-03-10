# -*- coding: utf-8 -*-
"""
Created on Sat Jan 22 18:01:38 2022

@author: mehdi
"""
#in reality each block consists of 64 bits
m='1010100011'
key='2341'
#encrypt a given message using the key k
def ECB(m,k):
    rest=len(m)%len(k)
    if(rest>0):
        padding=len(k)-rest
        m=m.ljust(padding+len(m),'0')
    keyLength=len(m)-1
    i=0
    c=''
    while i<keyLength :
        for j in range(len(k)):
            c=c+m[int(key[j])+i-1]
        #c=c+m[int(key[0])+i-1]+m[int(key[1])+i-1]+m[int(key[2])+i-1]+m[int(key[3])+i-1]
        i=i+len(k)
        print(i)
    return c

print(ECB(m, key))
        
def ECBDecrypt(c,k):
    rest=len(c)%len(k)
    if(rest>0):
        padding=len(k)-rest
        c=c.ljust(padding+len(c),'0')
    keyLength=len(c)-1
    i=0
    m=''
    while i<keyLength :
        liste=[]
        for j in range(len(k)):
            liste.insert(int(key[j])-1, c[j+i])
       # liste.insert(int(key[0])-1, c[0+i])
       # liste.insert(int(key[1])-1, c[1+i])
        #liste.insert(int(key[2])-1, c[2+i])
       # liste.insert(int(key[3])-1, c[3+i])
        m=m+"".join(liste)
        i=i+len(k)
       
    return m

print(ECBDecrypt(ECB(m, key), key))