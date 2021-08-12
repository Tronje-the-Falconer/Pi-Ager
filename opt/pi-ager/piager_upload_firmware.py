import argparse
import asyncio
import logging

if __name__ == '__main__':
    import globals
    # init global threading.lock
    globals.init()
    
from pi_ager_nextion.client import Nextion
from pi_ager_nextion.constants import BAUDRATES

import pi_ager_names
import pi_ager_database

async def upload(args):
    nextion = Nextion(args.device, args.baud, reconnect_attempts=1)
    
    try:
        await nextion.connect()
        await nextion.upload_firmware(args.file, args.upload_baud)
    except Exception as e:
        logging.exception("Failed to upload firmware: " + str(e))
        pi_ager_database.update_nextion_table(0, "failed")

def main():
    parser = argparse.ArgumentParser()
    parser.add_argument("device", help="device serial port")
    parser.add_argument(
        "-b", "--baud", type=int, default=None, help="baud rate", choices=BAUDRATES
    )
    parser.add_argument(
        "-ub",
        "--upload_baud",
        type=int,
        default=115200,
        help="upload baud rate",
        choices=BAUDRATES,
    )
    parser.add_argument(
        "-v", "--verbose", action="store_true", help="output debug messages"
    )
    parser.add_argument(
        "file", type=argparse.FileType("br"), help="firmware file *.tft"
    )

    args = parser.parse_args()

    logging.basicConfig(
        level=logging.DEBUG if args.verbose else logging.INFO,
        format="%(asctime)s %(levelname)-8s %(name)-15s %(message)s",
    )

    loop = asyncio.get_event_loop()
    loop.run_until_complete(upload(args))


if __name__ == "__main__":
    main()
