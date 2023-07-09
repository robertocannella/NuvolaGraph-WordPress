
import {useEffect, useRef, useState} from '@wordpress/element';
const selectBoxStyles = 'cursor-pointer px-2 hover:bg-violet-100';

export default function TimeSelect() {
    const [selectedHour, setSelectedHour] = useState('09');
    const [selectedMinute, setSelectedMinute] = useState('30');
    const [selectedPeriod, setSelectedPeriod] = useState('AM');

    const hourRef = useRef(null);
    const minuteRef = useRef(null);

    const hours = Array.from({ length: 12 }, (_, index) => {
        const hour = (index + 1).toString().padStart(2, '0');
        return { value: hour, label: hour };
    });


    const minutes = Array.from({ length: 60 }, (_, index) => {
        const minute = index.toString().padStart(2, '0');
        return { value: minute, label: minute };
    });

    const periods = [
        { value: 'AM', label: 'AM' },
        { value: 'PM', label: 'PM' },
    ];

    const handleHourChange = (value) => {
        setSelectedHour(value);
    };

    const handleMinuteChange = (value) => {
        setSelectedMinute(value);
    };

    const handlePeriodChange = (value) => {
        setSelectedPeriod(value);
    };


    useEffect(() => {
        setTimeout(() => {
            if (hourRef.current) {
                hourRef.current.scrollIntoView({ behavior: 'instant', block: 'start' });
            }
            if (minuteRef.current) {
                minuteRef.current.scrollIntoView({ behavior: 'instant',block:'start' });
            }
        }, 10);
    }, [hourRef, minuteRef]);
    const renderOptions = (options, selectedValue, handleOptionChange, ref) => {
        return options.map((option) => (
            <li
                key={option.value}
                className={
                    selectedValue === option.value
                        ? 'selected bg-violet-200 border border-violet-400' + selectBoxStyles
                        : selectBoxStyles
                }
                ref={selectedValue === option.value ? ref : null}
                onClick={() => handleOptionChange(option.value)}
            >
                {option.label}
            </li>
        ));
    };


    return (
        <div className="absolute bg-white shadow-2xl border p-2 text-lg rounded">
            <div className="selected-option">
                {selectedHour}:{selectedMinute} {selectedPeriod}
            </div>
            <div className="custom-select h-32 flex border-slate-500 border rounded">
                <ul className="options no-scrollbar overflow-scroll mx-2">
                    {renderOptions(hours, selectedHour, handleHourChange, hourRef)}
                </ul>
                <ul className="options no-scrollbar overflow-scroll mx-2">
                    {renderOptions(minutes, selectedMinute, handleMinuteChange, minuteRef)}
                </ul>
                <ul className="options mx-2">
                    {renderOptions(periods, selectedPeriod, handlePeriodChange, null)}
                </ul>
            </div>
        </div>
    );
}