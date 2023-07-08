import { useState } from '@wordpress/element';
import SubListGroup from "./SubListGroup";

interface Props {
    heading: string;
    items: string[];
}

let listItemStyles = "w-full border-b-2 border-neutral-100 border-opacity-100 py-2 px-1 cursor-pointer dark:border-opacity-50";


export default function ListGroup({items, heading}: Props) {

    const [times, setTimes] = useState(["time1","time2","time2"]);

    const [selectedItem, setSelectedItem] = useState('')
    const handleClick = (item) => {
        setSelectedItem(item)
        console.log(item)
    }
    const handleOnDelete = (item) =>{
        console.log("filtering Items")
        let updatedTimes = times.filter(i => i != item);
        setTimes([...updatedTimes]);
    }

    return (
        <>
            <h1 className={"text-lg"}>{heading}</h1>
            <ul className="">
                {items.map(item =>
                    (<li
                        className={selectedItem === item ? 'bg-violet-200 ' + listItemStyles: 'hover:bg-slate-200 ' + listItemStyles }
                        onClick={()=>handleClick(item)}
                        key={item}>{(selectedItem !== item) && item }{selectedItem === item &&
                        <SubListGroup heading={item} items={times} onDelete={handleOnDelete}/>
                        }
                    </li>)
                )}

            </ul>
        </>

    );
}