import {useState} from '@wordpress/element';

interface Props {
    heading: string;
    timeSlots: string[];
    onDelete: (item:string) => void;

}

let listItemStyles = "w-full border-b-2 border-neutral-100 border-opacity-100 py-2 px-1 cursor-pointer dark:border-opacity-50";


export default function HourlyListGroup({timeSlots, heading, onDelete}: Props) {


    const [selectedItem, setSelectedItem] = useState('')
    const handleClick = (item) => {
        setSelectedItem(item)
        console.log(item)
    }
    return (
        <>
            <h1 className={"text-lg"}>{heading}<span className={"float-right"}>
                <i className="fa-solid fa-plus hover:text-green-400"></i>
            </span></h1>
            <ul className="">
                {timeSlots.map(timeSlot =>
                    (<>
                            <li
                                className={selectedItem === timeSlot ? 'bg-violet-200 ' + listItemStyles : 'hover:bg-violet-300 ' + listItemStyles}
                                onClick={() => handleClick(timeSlot)}
                                key={heading + timeSlot}>
                                {timeSlot} <span onClick={() => onDelete(timeSlot)} className={"float-right"}>
                                <i className="fa-solid fa-trash text-lg hover:text-red-400"></i>
                            </span>

                            </li>

                        </>
                    )
                )}

            </ul>
        </>

    );
}