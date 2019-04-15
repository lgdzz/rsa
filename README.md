# rsa
rsa加密解密

### 生成RSA私钥
```
# openssl genrsa -out rsa_private.pem 1024
```

### 生成RSA公钥
```
# openssl rsa -in rsa_private.pem -out rsa_public.pem -pubout
```

```
### 私钥加密 ###
privateEncrypt

### 公钥解密 ###
publicDecrypt
```

```
### 公钥加密 ###
publicEncrypt

### 私钥解密 ###
privateDecrypt
```