import logging

def logger_gen(name):
    logging.basicConfig(level=logging.INFO, format='%(asctime)s - %(filename)s[line:%(lineno)d] - %(levelname)s: %(message)s', datefmt="[%Y-%m-%d %I:%M:%S %p %Z]")
    logger = logging.getLogger(name)
    return logger