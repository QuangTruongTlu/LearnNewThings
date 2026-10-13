#!/usr/bin/env python3

import os
import subprocess

def main():
    # script “ngố” được root chạy định kỳ hoặc gán SUID
    # ban đầu chỉ log cho vui
    log_path = "/tmp/secret.log"
    with open(log_path, "a") as f:
        f.write("secret.py is running as: %s\n" % os.getuid())

if __name__ == "__main__":
    main()
