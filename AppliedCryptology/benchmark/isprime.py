#!/usr/bin/env python
NUM = 111181111
   
def is_prime(n): 
    i = 2
    while i < n: 
        if n % i == 0: 
            return False
        i += 1
    return True
  
print is_prime(NUM)
